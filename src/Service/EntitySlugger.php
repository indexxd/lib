<?php

namespace App\Service;

use App\Util\EntitySluggerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;

class EntitySlugger implements EntitySluggerInterface
{
    const LIMIT = 20;
    
    private $doctrine;
    private $slugger;
    

    public function __construct(SluggerInterface $slugger, ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->slugger = $slugger;
    }
    

    public function getUnique($entity, string $property): string
    {
        $reflectionClass = new \ReflectionClass($entity);
        
        $reflectionProperty = $reflectionClass->getProperty($property);
        $reflectionProperty->setAccessible(true);

        $entityRepository = $this->doctrine->getRepository($reflectionClass->getName());

        $slug = $this->createSlug($reflectionProperty->getValue($entity));
        
        if ($this->isUnique($slug, $entityRepository)) {
            return $slug;
        }
        
        return $this->findUnique($reflectionProperty, $entity, $entityRepository);

    }


    private function findUnique(\ReflectionProperty $reflectionProperty, $entity, ServiceEntityRepository $entityRepository): string
    {
        for ($i = 1; $i <= self::LIMIT; $i++) { 
            $slug = $this->createSlug($reflectionProperty->getValue($entity) . "-" . $i);

            if ($this->isUnique($slug, $entityRepository)) {
                return $slug;
            }
        }

        throw new \Exception("Could not find a unique slug.");
    }
    

    private function createSlug(string $string): string
    {
        return strtolower($this->slugger->slug($string));
    }

    
    private function isUnique(string $slug, ServiceEntityRepository $repository): bool
    {
        $results = $repository->findBy([ "slug" => $slug ]);
        
        return count($results) === 0;
    }
}