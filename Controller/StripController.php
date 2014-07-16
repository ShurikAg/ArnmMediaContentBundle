<?php
namespace Arnm\MediaContentBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Arnm\MediaContentBundle\Form\StripItemEditType;
use Arnm\MediaContentBundle\Model\StripItemEdit;
use Arnm\MediaContentBundle\Form\StripItemType;
use Arnm\MediaContentBundle\Model\StripItem;
use Arnm\MediaBundle\Entity\Attribute;
use Arnm\MediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\Response;
use Arnm\CoreBundle\Controllers\ArnmController;
/**
 * Controller for filmstrip items management
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class StripController extends ArnmController
{
    /**
     * Shows list of all filmstrip items
     *
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ArnmMediaBundle:Media')->findAllMediaByTag('film-strip');

        return $this->render('MediaContentBundle:Strip:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Shows a from for uploading new filmstrip item
     *
     * @return Response
     */
    public function newAction()
    {
        $strip = new StripItem();
        $form = $this->createForm(new StripItemType(), $strip);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                //create new media object
                $media = new Media();
                $media->setWebDir($this->getWebDir());
                $media->media = $strip->getMedia();
                $media->setName($strip->getTitle());
                $media->setTag('film-strip');

                $em = $this->getDoctrine()->getManager();

                $em->persist($media);
                //order
                $orderAttr = new Attribute();
                $orderAttr->setName('order');
                $orderAttr->setValue($strip->getOrder());
                $orderAttr->setMedia($media);

                $em->persist($orderAttr);
                //caption
                $captionAttr = new Attribute();
                $captionAttr->setName('caption');
                $captionAttr->setValue($strip->getCaption());
                $captionAttr->setMedia($media);

                $em->persist($captionAttr);

                //url
                $url = $strip->getUrl();
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
                    ->trans('strip-item.message.create.success', array(), 'media_content'));

                return $this->redirect($this->generateUrl('arnm_media_content_strip'));
            }
        }
        return $this->render('MediaContentBundle:Strip:new.html.twig',
        array(
            'strip' => $strip,
            'form' => $form->createView()
        ));
    }

    /**
     * Shows a from for editing existing filmstrip item
     *
     * @param int $id
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

        $strip = new StripItemEdit();
        //populate the object
        $this->populateStripItemEditObject($strip, $media);
        $form = $this->createForm(new StripItemEditType(), $strip);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                //create new media object
                $media->setName($strip->getTitle());
                $uploadedFile = $strip->getMedia();
                if ($uploadedFile instanceof UploadedFile) {
                    $media->media = $uploadedFile;
                    $media->setCreated(new \DateTime());
                }
                $em->persist($media);

                $orderAttr = $media->getAttributeByName('order');
                $orderAttr->setValue($strip->getOrder());
                $em->persist($orderAttr);

                //Caption
                $captionAttr = $media->getAttributeByName('caption');
                $caption = $strip->getCaption();
                if (! empty($caption)) {
                    if ($captionAttr instanceof Attribute) {
                        //update
                        $captionAttr->setValue($caption);
                    } else {
                        //create new
                        $captionAttr = new Attribute();
                        $captionAttr->setName('caption');
                        $captionAttr->setValue($caption);
                        $captionAttr->setMedia($media);
                    }
                    $em->persist($captionAttr);
                } else {
                    if ($captionAttr instanceof Attribute) {
                        //update
                        $em->remove($captionAttr);
                    }
                }
                //URL
                $urlAttr = $media->getAttributeByName('url');
                $url = $strip->getUrl();
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
                    ->trans('strip-item.message.update.success', array(), 'media_content'));

                return $this->redirect($this->generateUrl('arnm_media_content_strip_edit', array(
                    'id' => $media->getId()
                )));
            }
        }
        return $this->render('MediaContentBundle:Strip:edit.html.twig',
        array(
            'strip' => $strip,
            'media' => $media,
            'form' => $form->createView()
        ));
    }

    /**
     * Populates the StripItemEdit model with the data from media
     *
     * @param StripItemEdit $item
     * @param Media $media
     */
    protected function populateStripItemEditObject(StripItemEdit $item, Media $media)
    {
        $item->setTitle($media->getName());
        $item->setCaption($media->getAttributeValueByName('caption'));
        $item->setOrder($media->getAttributeValueByName('order'));
        $item->setUrl($media->getTargetUrl());
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
                    ->trans('strip-item.message.delete.success', array(), 'media_content'));
        }

        return $this->redirect($this->generateUrl('arnm_media_content_strip'));
    }
}
