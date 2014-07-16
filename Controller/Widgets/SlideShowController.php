<?php
namespace Arnm\MediaContentBundle\Controller\Widgets;

use Symfony\Component\Form\FormError;

use Symfony\Component\HttpFoundation\Request;

use Arnm\MediaBundle\Entity\Media;

use Symfony\Component\HttpFoundation\Response;

use Arnm\MediaContentBundle\Form\SlideShowType;

use Arnm\WidgetBundle\Entity\Param;

use Arnm\MediaContentBundle\Model\SlideShow;

use Arnm\WidgetBundle\Entity\Widget;

use Arnm\WidgetBundle\Controllers\ArnmWidgetController;
/**
 * Slide show widget controller
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class SlideShowController extends ArnmWidgetController
{

    /**
     *
     * {@inheritdoc}
     * @see Arnm\WidgetBundle\Controllers.ArnmWidgetController::renderAction()
     */
    public function renderAction(Widget $widget)
    {
        $tagParam = $widget->getParamByName('slides-tag');

        $em = $this->getEntityManager();
        $slides = $em->getRepository('ArnmMediaBundle:Media')->findAllMediaByTag((string) $tagParam->getValue());

        //sort the media
        usort($slides, array(
            $this,
            'sort'
        ));

        return $this->render('MediaContentBundle:Widgets\SlideShow:render.html.twig', array(
            'slides' => $slides
        ));
    }

    /**
     * Function that used to sort the list of media based of the order attribute
     *
     * @param Media $media1
     * @param Media $media2
     *
     * @return int
     */
    protected function sort(Media $media1, Media $media2)
    {
        return (((int) $media1->getAttributeValueByName('order')) - ((int) $media2->getAttributeValueByName('order')));
    }

    /**
     * {@inheritdoc}
     * @see Arnm\WidgetBundle\Controllers.ArnmWidgetController::getConfigFields()
     */
    public function getConfigFields()
    {
        return array(
            'slides_tag'
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

        $slideShow = new SlideShow();
        $form = $this->createForm(new SlideShowType(), $slideShow);

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
     * Shows and processes edit widget of the slideshow widget
     *
     * {@inheritdoc}
     * @see Arnm\WidgetBundle\Controllers.ArnmWidgetController::editAction()
     */
    public function editAction()
    {
        //create the form
        $slideShow = new SlideShow();
        $form = $this->createForm(new SlideShowType(), $slideShow);
        return $this->render('MediaContentBundle:Widgets\SlideShow:edit.html.twig', array(
            'edit_form' => $form->createView()
        ));
    }

    /**
     * Creates new of updates existing param of the widget
     *
     * @param Widget $widget
     * @param SlideShow $slideShow
     */
    protected function processSaveParam(Widget $widget, SlideShow $slideShow)
    {
        //find the widget
        $param = $widget->getParamByName('slides_tag');
        $em = $this->getEntityManager();
        if ($param instanceof Param) {
            //update existing
            $param->setValue((string) $slideShow->getSlidesTag());
        } else {
            //create new
            $param = new Param();
            $param->setName('slides_tag');
            $param->setValue((string) $slideShow->getSlidesTag());
            $param->setWidget($widget);
        }

        $em->persist($param);
        $em->flush();
    }

}