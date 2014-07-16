<?php
namespace Arnm\MediaContentBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
/**
 * Template form use to manage Templates as well as gets embedded into page form
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class SlideType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('media', 'file', array(
        'label' => 'slide.form.media.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'slide.form.media.help',
            'translation_domain' => 'media_content',
            'class' => ''
        ),
        'translation_domain' => 'media_content',
        'required' => false
    ));
    $builder->add('title', 'text', array(
        'label' => 'slide.form.title.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'slide.form.title.help',
            'translation_domain' => 'media_content',
            'class' => 'form-control'
        ),
        'translation_domain' => 'media_content',
        'required' => false
    ));
    $builder->add('order', 'text', array(
        'label' => 'slide.form.order.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'slide.form.order.help',
    		'translation_domain' => 'media_content',
            'class' => 'form-control'
        ),
        'translation_domain' => 'media_content',
        'required' => false
    ));
    $builder->add('url', 'text', array(
        'label' => 'slide.form.url.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'slide.form.url.help',
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
    return 'slide';
  }

  /**
   * (non-PHPdoc)
   * @see Symfony\Component\Form.AbstractType::getDefaultOptions()
   */
  public function getDefaultOptions(array $options)
  {
    return array(
        'data_class' => 'Arnm\MediaContentBundle\Model\Slide'
    );
  }
}
