<?php
namespace Arnm\MediaContentBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
/**
 * Filmstrip widget form
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class FilmstripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('items_tag', 'text', array(
            'label' => 'filmstrip.form.tag.label',
            'attr' => array(
                'data-toggle' => 'popover',
                'content' => 'filmstrip.form.tag.help',
                'class' => 'form-control',
                'ng-model' => 'wConfigForm.items_tag',
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
        return 'filmstrip';
    }

    /**
     * {@inheritdoc}
     * @see Symfony\Component\Form.AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Arnm\MediaContentBundle\Model\Filmstrip',
            'csrf_protection' => false
        ));
    }
}
