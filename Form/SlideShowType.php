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
class SlideShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('slides_tag', 'text', array(
            'label' => 'slideshow.form.tag.label',
            'attr' => array(
                'data-toggle' => 'popover',
                'content' => 'slideshow.form.tag.help',
                'class' => 'form-control',
                'ng-model' => 'wConfigForm.slides_tag',
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
        return 'slideshow';
    }

    /**
     * {@inheritdoc}
     * @see Symfony\Component\Form.AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Arnm\MediaContentBundle\Model\SlideShow',
            'csrf_protection' => false
        ));
    }
}
