<?php
namespace Arnm\MediaContentBundle\Controller\Widgets;

use Symfony\Component\Form\FormError;

use Symfony\Component\HttpFoundation\Request;

use Arnm\MediaContentBundle\Form\FilmstripType;

use Arnm\MediaContentBundle\Model\Filmstrip;
use Arnm\MediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\Response;
use Arnm\WidgetBundle\Entity\Param;
use Arnm\WidgetBundle\Entity\Widget;
use Arnm\WidgetBundle\Controllers\ArnmWidgetController;
/**
 * Filmstrip widget controller
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class FilmStripController extends ArnmWidgetController
{

    /**
     *
     * {@inheritdoc}
     * @see Arnm\WidgetBundle\Controllers.ArnmWidgetController::renderAction()
     */
    public function renderAction(Widget $widget)
    {
        $tagParam = $widget->getParamByName('items-tag');

        $em = $this->getEntityManager();
        $items = $em->getRepository('ArnmMediaBundle:Media')->findAllMediaByTag((string) $tagParam->getValue());

        //sort the media
        usort($items, array(
            $this,
            'sort'
        ));

        return $this->render('MediaContentBundle:Widgets\Filmstrip:render.html.twig', array(
            'items' => $items
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
            'items_tag'
        );
    }

    /**
     * {@inheritdoc}
     * @see Arnm\WidgetBundle\Controllers.ArnmWidgetController::updateAction()
     */
    public function updateAction($id, Request $request)
    {
        $widget = $this->getWidgetManager()->findWidgetById($id);
        if (!($widget instanceof Widget)) {
            throw $this->createNotFoundException("Widget with id: '" . $id . "' not found!");
        }

        $filmstrip = new Filmstrip();
        $form = $this->createForm(new FilmstripType(), $filmstrip);

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

        $this->processSaveParam($widget, $filmstrip);

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
        $filmstrip = new Filmstrip();
        $form = $this->createForm(new FilmstripType(), $filmstrip);

        return $this->render('MediaContentBundle:Widgets\Filmstrip:edit.html.twig', array(
            'edit_form' => $form->createView()
        ));
    }

    /**
     * Creates new of updates existing param of the widget
     *
     * @param Widget $widget
     * @param Filmstrip $filmstrip
     */
    protected function processSaveParam(Widget $widget, Filmstrip $filmstrip)
    {
        //find the widget
        $param = $widget->getParamByName('items_tag');
        $em = $this->getEntityManager();
        if ($param instanceof Param) {
            //update existing
            $param->setValue((string) $filmstrip->getItemsTag());
        } else {
            //create new
            $param = new Param();
            $param->setName('items_tag');
            $param->setValue((string) $filmstrip->getItemsTag());
            $param->setWidget($widget);
        }

        $em->persist($param);
        $em->flush();
    }

    /**
     * Fills te slideshow object with data from the widget's params
     *
     * @param Widget $widget
     * @param Filmstrip $filmstrip
     */
    protected function fillDataObject(Widget $widget, Filmstrip $filmstrip)
    {
        $param = $widget->getParamByName('items-tag');
        if ($param instanceof Param) {
            $filmstrip->setTag((string) $param->getValue());
        }
    }
}

?>