<?php
namespace Arnm\MediaContentBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * A model for slideshow widget management
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class SlideShow
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
    private $slidesTag;

    /**
     * Sets the resired tag of the slides
     *
     * @param string $tag
     */
    public function setSlidesTag($tag)
    {
        $this->slidesTag = (string) $tag;
    }

    /**
     * Gets currently set tag
     *
     * @return string
     */
    public function getSlidesTag()
    {
        return $this->slidesTag;
    }
}
