<?php
namespace Arnm\MediaContentBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
/**
 * Slide show widget form
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class SmallSlideShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tag', 'text', array(
            'label' => 'small_slideshow.form.tag.label',
            'attr' => array(
                'data-toggle' => 'popover',
                'content' => 'small_slideshow.form.tag.help',
                'class' => 'form-control',
                'ng-model' => 'wConfigForm.tag',
                'translation_domain' => 'media_content'
            ),
            'translation_domain' => 'media_content',
            'required' => false
        ));
        $builder->add('slideCount', 'text', array(
            'label' => 'small_slideshow.form.slideCount.label',
            'attr' => array(
                'data-toggle' => 'popover',
                'content' => 'small_slideshow.form.slideCount.help',
                'class' => 'form-control',
                'ng-model' => 'wConfigForm.slideCount',
                'translation_domain' => 'media_content'
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
        return 'small_slideshow';
    }

    /**
     * {@inheritdoc}
     * @see Symfony\Component\Form.AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Arnm\MediaContentBundle\Model\SmallSlideShow',
            'csrf_protection' => false
        ));
    }
}
