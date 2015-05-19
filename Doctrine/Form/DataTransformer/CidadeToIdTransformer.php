<?php
/**
 * This file is part of the Duo Criativa software.
 *
 * (c) Paulo Ribeiro <paulo@duocriativa.com.br>
 *
 * Date: 1/31/12
 * Time: 12:08 PM
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BFOS\BrasilBundle\Doctrine\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CidadeToIdTransformer implements DataTransformerInterface
{

    protected $em;
    protected $class = 'BFOSBrasilBundle:Cidade';
    //protected $propertyPath = 'nome';

    public function __construct(EntityManager $em)
    {
        $this->em = $em;

        // The property option defines, which property (path) is used for
        // displaying entities as strings
        //$this->propertyPath = new PropertyPath($this->propertyPath);
    }


    /**
     * Transforms the Cidade entity to a composed (estado,cidade_id) array value in the form
     *
     * @param $entity
     * @return int|null
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     */
    public function transform($entity)
    {
        if (null === $entity || '' === $entity) {
            return null;
        }

        if (!is_object($entity)) {
            throw new UnexpectedTypeException($entity, 'object');
        }
        return $entity->getId();

    }

    /**
     * Takes an
     * @param $key
     * @return null
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     */
    public function reverseTransform($key)
    {
        if ('' === $key || null === $key) {
            return null;
        }

        if (!is_integer($key)) {
            throw new TransformationFailedException(sprintf('O id "%s" da cidade não é válido', $key));
        }

        $entity = $this->em->getRepository($this->class)->findOneById($key);

        if ($entity === null) {
            throw new TransformationFailedException(sprintf('A cidade com id "%s" não foi encontrada.', $key));
        }

        return $entity;
    }
}