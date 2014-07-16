<?php
namespace Arnm\MediaContentBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Arnm\MediaContentBundle\Form\SlideEditType;
use Arnm\MediaContentBundle\Model\SlideEdit;
use Arnm\MediaBundle\Entity\Attribute;
use Arnm\MediaBundle\Entity\Media;
use Arnm\MediaContentBundle\Form\SlideType;
use Arnm\MediaContentBundle\Model\Slide;
use Symfony\Component\HttpFoundation\Response;
use Arnm\CoreBundle\Controllers\ArnmController;
/**
 * Controller for slides management
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class SlideController extends ArnmController
{
    /**
     * Shows list of all slides
     *
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ArnmMediaBundle:Media')->findAllMediaByTag('slide-show');

        return $this->render('MediaContentBundle:Slide:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Shows a from for uploading new slide
     *
     * @return Response
     */
    public function newAction()
    {
        $slide = new Slide();
        $form = $this->createForm(new SlideType(), $slide);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                //create new media object
                $media = new Media();
                $media->setWebDir($this->getWebDir());
                $media->media = $slide->getMedia();
                $media->setName($slide->getTitle());
                $media->setTag('slide-show');

                $em = $this->getDoctrine()->getManager();

                $em->persist($media);

                $orderAttr = new Attribute();
                $orderAttr->setName('order');
                $orderAttr->setValue($slide->getOrder());
                $orderAttr->setMedia($media);

                $em->persist($orderAttr);

                $url = $slide->getUrl();
                if (! empty($url)) {
                    //create an attribute
                    $attribute = new Attribute();
                    $attribute->setName('url');
                    $attribute->setValue($url);
                    $attribute->setMedia($media);

                    $em->persist($attribute);
                }

                $em->flush();

                $this->getSession()
                    ->getFlashBag()
                    ->add('notice', $this->get('translator')
                    ->trans('slide.message.create.success', array(), 'media_content'));

                return $this->redirect($this->generateUrl('arnm_media_content_slide'));
            }
        }
        return $this->render('MediaContentBundle:Slide:new.html.twig',
        array(
            'slide' => $slide,
            'form' => $form->createView()
        ));
    }
    /**
     * Shows a from for editing existing slide
     *
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $media = $em->getRepository('ArnmMediaBundle:Media')->findOneById($id);
        $media->setWebDir($this->getWebDir());

        if (! ($media instanceof Media)) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $slide = new SlideEdit();
        //populate the object
        $this->populateSlideEditObject($slide, $media);
        $form = $this->createForm(new SlideEditType(), $slide);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                //create new media object
                $media->setName($slide->getTitle());
                $uploadedFile = $slide->getMedia();
                if ($uploadedFile instanceof UploadedFile) {
                    $media->media = $uploadedFile;
                    $media->setCreated(new \DateTime());
                }
                $em->persist($media);

                $orderAttr = $media->getAttributeByName('order');
                $orderAttr->setValue($slide->getOrder());
                $em->persist($orderAttr);

                $urlAttr = $media->getAttributeByName('url');
                $url = $slide->getUrl();
                if (! empty($url)) {
                    if ($urlAttr instanceof Attribute) {
                        //update
                        $urlAttr->setValue($url);
                    } else {
                        //create new
                        $urlAttr = new Attribute();
                        $urlAttr->setName('url');
                        $urlAttr->setValue($url);
                        $urlAttr->setMedia($media);
                    }
                    $em->persist($urlAttr);
                } else {
                    if ($urlAttr instanceof Attribute) {
                        //update
                        $em->remove($urlAttr);
                    }
                }

                $em->flush();

                $this->getSession()
                    ->getFlashBag()
                    ->add('notice', $this->get('translator')
                    ->trans('slide.message.update.success', array(), 'media_content'));

                return $this->redirect($this->generateUrl('arnm_media_content_slide_edit', array(
                    'id' => $media->getId()
                )));
            }
        }

        return $this->render('MediaContentBundle:Slide:edit.html.twig',
        array(
            'slide' => $slide,
            'media' => $media,
            'form' => $form->createView()
        ));
    }

    /**
     * Populates the SlideEdit model with the data from media
     *
     * @param SlideEdit $slide
     * @param Media $media
     */
    protected function populateSlideEditObject(SlideEdit $slide, Media $media)
    {
        $slide->setTitle($media->getName());
        $slide->setOrder($media->getAttributeValueByName('order'));
        $slide->setUrl($media->getTargetUrl());
    }

    /**
     * Deletes a slide
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $media = $em->getRepository('ArnmMediaBundle:Media')->findOneById($id);
            $media->setWebDir($this->getWebDir());

            if (! ($media instanceof Media)) {
                throw $this->createNotFoundException('Unable to find Media entity.');
            }

            foreach ($media->getAttributes() as $attr) {
                $em->remove($attr);
            }
            $em->remove($media);
            $em->flush();

            $this->getSession()
                ->getFlashBag()
                ->add('notice', $this->get('translator')
                ->trans('slide.message.delete.success', array(), 'media_content'));
        }

        return $this->redirect($this->generateUrl('arnm_media_content_slide'));
    }
}
