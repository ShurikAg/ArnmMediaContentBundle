<?php
namespace Arnm\MediaContentBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
/**
 * Template form use to manage Templates as well as gets embedded into page form
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class StripItemType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('media', 'file', array(
        'label' => 'strip.form.media.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'strip.form.media.help',
            'translation_domain' => 'media_content',
            'class' => ''
        ),
        'translation_domain' => 'media_content',
        'required' => false
    ));
    $builder->add('title', 'text', array(
        'label' => 'strip.form.title.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'strip.form.title.help',
            'translation_domain' => 'media_content',
            'class' => 'form-control'
        ),
        'translation_domain' => 'media_content',
        'required' => false
    ));
    $builder->add('caption', 'text', array(
        'label' => 'strip.form.caption.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'strip.form.caption.help',
            'translation_domain' => 'media_content',
            'class' => 'form-control'
        ),
        'translation_domain' => 'media_content',
        'required' => false
    ));
    $builder->add('order', 'text', array(
        'label' => 'strip.form.order.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'strip.form.order.help',
            'translation_domain' => 'media_content',
            'class' => 'form-control'
        ),
        'translation_domain' => 'media_content',
        'required' => false
    ));
    $builder->add('url', 'text', array(
        'label' => 'strip.form.url.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'strip.form.url.help',
            'translation_domain' => 'media_content',
            'class' => 'form-control'
        ),
        'translation_domain' => 'media_content',
        'required' => false
    ));
  }

  /**
   * (non-PHPdoc)
   * @see Symfony\Component\Form.FormTypeInterface::getName()
   */
  public function getName()
  {
    return 'stip_item';
  }

  /**
   * (non-PHPdoc)
   * @see Symfony\Component\Form.AbstractType::getDefaultOptions()
   */
  public function getDefaultOptions(array $options)
  {
    return array(
        'data_class' => 'Arnm\MediaContentBundle\Model\StripItem'
    );
  }
}
