<?php
namespace Arnm\MediaContentBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * A model for slideshow widget management
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class SmallSlideShow
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
    private $tag;

    /**
     * Number of images to use in the slide show.
     *
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\Range(min = "1", minMessage = "At least one image must be in the slide show")
     */
    private $slideCount;

    /**
     * Sets the resired tag of the slides
     *
     * @param string $tag
     */
    public function setTag($tag)
    {
        $this->tag = (string) $tag;
    }

    /**
     * Gets currently set tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Sets slide count for the slide show.
     *
     * @param int $slideCount
     */
    public function setSlideCount($slideCount)
    {
        $this->slideCount = (int) $slideCount;
    }

    /**
     * Gets the number of slides to use in the slide show
     *
     * @return int
     */
    public function getSlideCount()
    {
        return $this->slideCount;
    }
}
