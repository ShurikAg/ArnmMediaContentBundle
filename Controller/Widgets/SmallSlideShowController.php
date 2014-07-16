<?php
namespace Arnm\MediaContentBundle\Controller\Widgets;

use Symfony\Component\Form\FormError;

use Symfony\Component\HttpFoundation\Request;

use Arnm\MediaContentBundle\Form\SmallSlideShowType;

use Arnm\MediaContentBundle\Model\SmallSlideShow;

use Arnm\WidgetBundle\Entity\Widget;

use Arnm\MediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\Response;
use Arnm\WidgetBundle\Entity\Param;
use Arnm\WidgetBundle\Controllers\ArnmWidgetController;
/**
 * Slide show widget controller for the inpage slideshow
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class SmallSlideShowController extends ArnmWidgetController
{

    /**
     *
     * {@inheritdoc}
     * @see Arnm\WidgetBundle\Controllers.ArnmWidgetController::renderAction()
     */
    public function renderAction(Widget $widget)
    {
        $tagParam = $widget->getParamByName('tag');
        $countParam = $widget->getParamByName('count');

        $em = $this->getEntityManager();
        $slides = $em->getRepository('ArnmMediaBundle:Media')->findAllMediaByTag((string) $tagParam->getValue(), (int) $countParam->getValue());

        return $this->render('MediaContentBundle:Widgets\SmallSlideShow:render.html.twig', array(
            'slides' => $slides
        ));
    }

    /**
     * Shows and processes edit widget of the slideshow widget
     *
     * {@inheritdoc}
     * @see Arnm\WidgetBundle\Controllers.ArnmWidgetController::editAction()
     */
    public function editAction()
    {
        $slideShow = new SmallSlideShow();
        $form = $this->createForm(new SmallSlideShowType(), $slideShow);
        return $this->render('MediaContentBundle:Widgets\SmallSlideShow:edit.html.twig', array(
            'edit_form' => $form->createView()
        ));
    }

    /**
     * {@inheritdoc}
     * @see Arnm\WidgetBundle\Controllers.ArnmWidgetController::getConfigFields()
     */
    public function getConfigFields()
    {
        return array(
            'tag',
            'slideCount'
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see Arnm\WidgetBundle\Controllers.ArnmWidgetController::updateAction()
     */
    public function updateAction($id, Request $request)
    {
        $widget = $this->getWidgetManager()->findWidgetById($id);
        if (!($widget instanceof Widget)) {
            throw $this->createNotFoundException("Widget with id: '" . $id . "' not found!");
        }

        $slideShow = new SmallSlideShow();
        $form = $this->createForm(new SmallSlideShowType(), $slideShow);

        $data = $this->extractArrayFromRequest($request);

        $form->bind($data);
        if (!$form->isValid()) {
            $response = array(
                'error' => 'validation',
                'errors' => array()
            );
            $errors = $form->getErrors();
            foreach ($errors as $key => $error) {
                if ($error instanceof FormError) {
                    $response['errors'][$key] = $error->getMessage();
                }
            }

            return $this->createResponse($response);
        }

        $this->processSaveParam($widget, $slideShow);

        return $this->createResponse(array(
            'OK'
        ));
    }

    /**
     * Creates new of updates existing param of the widget
     *
     * @param Widget $widget
     * @param SmallSlideShow $slideShow
     */
    protected function processSaveParam(Widget $widget, SmallSlideShow $slideShow)
    {
        //find the widget
        $em = $this->getEntityManager();

        $tagParam = $widget->getParamByName('tag');
        if ($tagParam instanceof Param) {
            //update existing
            $tagParam->setValue((string) $slideShow->getTag());
        } else {
            //create new
            $tagParam = new Param();
            $tagParam->setName('tag');
            $tagParam->setValue((string) $slideShow->getTag());
            $tagParam->setWidget($widget);
        }
        $em->persist($tagParam);

        $countParam = $widget->getParamByName('slideCount');
        if ($countParam instanceof Param) {
            //update existing
            $countParam->setValue((string) $slideShow->getSlideCount());
        } else {
            //create new
            $countParam = new Param();
            $countParam->setName('slideCount');
            $countParam->setValue((string) $slideShow->getSlideCount());
            $countParam->setWidget($widget);
        }
        $em->persist($countParam);

        $em->flush();
    }

}
