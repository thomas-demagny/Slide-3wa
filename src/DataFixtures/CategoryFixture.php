<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    const CATEGORY_DATA = [
        ['name' => 'Front'],
        ['name' => 'Flip'],
        ['name' => 'Back'],
        ['name' => 'Rotations'],
        ['name' => 'Grabs']
    ];

    public function load(ObjectManager $manager)
    {

        for ($ca = 0; $ca < count(self::CATEGORY_DATA); ++$ca) {

            $category = $this->initCategory(new Category(),self::CATEGORY_DATA[$ca]);;

            $manager->persist($category);

            $this->addReference('category' .$ca, $category);
        }
        $manager->flush();
    }

    public function initCategory(Category $category, $data ): Category
    {
        $category
            ->setName($data['name']);

        return $category;
    }
}