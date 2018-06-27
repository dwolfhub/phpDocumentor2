<?php
declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2010-2018 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Descriptor\Type;

use phpDocumentor\Descriptor\Interfaces\TypeInterface;

/**
 * This class represents any Type that could not be identified.
 *
 * Sometimes DocBlocks refer to types, such as classes, that are outside the generated project's scope; in these
 * cases we want to identify them as such with an unknown type descriptor.
 */
class UnknownTypeDescriptor implements TypeInterface
{
    /** @var string Name/Identifier of the unknown type */
    protected $name;

    /**
     * Creates an unknown type with the given name.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the name for this identifier.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns a human-readable name for this type.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
