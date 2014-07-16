<?php
namespace Arnm\MediaContentBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * A model for slideshow widget management
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class Filmstrip
{

    /**
     * Tag of the image files we are interested in for this slideshow
     *
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $itemsTag;

    /**
     * Sets the resired tag of the slides
     *
     * @param string $tag
     */
    public function setItemsTag($tag)
    {
        $this->itemsTag = (string) $tag;
    }

    /**
     * Gets currently set tag
     *
     * @return string
     */
    public function getItemsTag()
    {
        return $this->itemsTag;
    }
}
