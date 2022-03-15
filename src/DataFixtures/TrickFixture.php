<?php

namespace App\DataFixtures;


use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


/**
 * Class TrickFixtures
 * @package App\DataFixtures
 */
class TrickFixture extends Fixture implements DependentFixtureInterface
{

    const TRICKS_DATA =
        [
            [
                'title' => 'Backside air',
                'description' => "Le grab star du snowboard qui peut être fait d'autant de façon différentes qu'il y a de styles de riders.
                Il consiste à attraper la carre arrière entre les pieds, ou légèrement devant, et à pousser avec sa jambe arrière pour ramener la planche devant.
                C'est une figure phare en pipe ou sur un hip en backside. C'est généralement avec ce trick que les riders vont le plus haut."
            ],
            [
                'title' => 'Mc Twist',
                'description' => "Un grand classique des rotations tête en bas qui se fait en backside, sur un mur backside de pipe.
                Le Mc Twist est généralement fait en japan, un grab très tweaké (action d'accentuer un grab en se contorsionnant)."
            ],
            [
                'title' => 'Cork',
                'description' => "Le diminutif de corkscrew qui signifie littéralement tire-bouchon et désignait les premières simples rotations têtes en bas en frontside.
                Désormais, on utilise le mot cork à toute les sauces pour qualifier les figures où le rider passe la tête en bas, peu importe le sens de rotation.
                Et dorénavant en compétition, on parle souvent de double cork, triple cork et certains riders vont jusqu'au quadruple cork !"
            ],
            [
                'title' => 'Crippler',
                'description' => "Une autre rotation tête en bas classique qui s'apparente à un backflip sur un mur frontside de pipe ou un quarter."
            ],
            [
                'title' => 'Backside rodeo',
                'description' => "Une rotation tête en bas backside tournant dans le sens d'un backflip qui peut se faire aussi bien sur un kicker, un pipe ou un hip."
            ],
            [
                'title' => 'Air to fakie',
                'description' => "En pipe, sur un quarter ou un hip, ce terme désigne un saut sans rotation où le rider retombe dans le sens inverse."
            ],
            [
                'title' => 'Handplant',
                'description' => "Un trick inspiré du skate qui consiste à tenir en équilibre sur une ou deux mains au sommet d'une courbe.
                Existe avec de nombreuses variantes dans les grabs et les rotations."
            ],
            [
                'title' => 'Nosegrab',
                'description' => "grabez l'avant de sa planche, très difficile à effectuer en rotation mais c'est le grab qui revient à la mode (dans une rotation)."
            ],
            [
                'title' => 'Mistyflip',
                'description' => "En backside : c'est une rotation back mélangée avec un frontflip, effectuée dans un pipe, c'est un mac-twist, l'impulsion à lieu sur les pointes de                                   pied."
            ],
            [
                'title' => 'Rodéofront',
                'description' => "En frontside : rotation front avec une impulsion très marquée sur les pointes de pieds."
            ],
            [
                'title' => 'Le switch',
                'description' => "On appelle switch le fait de rider à l'envers (goofy en régular et vice et versa).
                L'ensemble des tricks susnommés peuvent s'effectuer en switch cependant l'héritage du skate nous a imposé des termes spécifique pour certaines figures.
                Ainsi une rotation front effectuée en switch sera un cab (de CABALLERO inventeur du caballerial figure de skate).
                Ex : un switch 720° front peut s'appeler un cab 720°.
                Le fakie est aussi un terme emprunté du skate mais il est de moins en moins utilisé dans le snowboard (fakie signifiant rouler à l'envers)."
            ],
            [
                'title' => 'Nollie',
                'description' => "Un saut que l'on fait en sautant du nose de la planche."
            ],
            [
                'title' => 'FrontFlip',
                'description' => "Le frontflip est l'un des flips les plus faciles à réaliser en snowboard.
    
                Avant de l'essayer sur la neige, pratiquez le flip sur un trampoline.
                1.Accélérez sur une plateforme. 
                Pour obtenir une bonne rotation, poussez sur le tail avant le saut, puis passez rapidement en avant et poussez avec votre jambe avant.              
                2.Assurez-vous que vos épaules soient parallèles à la planche.
                3.Une fois en l'air, relevez les genoux et trouvez votre point d'atterrissage.
                4.Posez votre planche à plat."

            ],
            [
                'title' => 'BackFlip',
                'description' => "Le backflip est l'un des plus beaux tours qui existent. C'est souvent le seul moyen d'impressionner les spectateurs sur la piste.
                Pour se familiariser avec le flip, il est recommandé de s'entraîner d'abord au backflip sur un trampoline.
                Commencez la pirouette avec votre bras avant. Faites le saut avec votre jambe arrière. Assurez-vous que vos bras et vos épaules restent parallèles à la planche.
                En l'air, regardez par-dessus votre bras avant.
                Atterrissez sur une planche plate, en vous appuyant sur la pointe de vos pieds."
            ],
            [
                'title' => 'FS 540',
                'description' => "La prochaine étape logique après le frontside 360 est le frontside 540. Nous sommes en train de faire un grand pas en avant !
                1.Accélérez en diagonale. Assurez-vous que vous exécutez le pop off proprement.
                Lorsque vous lancez, faites une légère rotation avec vos épaules. Maintenant, levez les genoux et tournez les épaules dans le sens de la rotation.
                La position est identique à celle du fs 180."
            ]
        ];

    public function load(ObjectManager $manager)
    {
        foreach (self::TRICKS_DATA as $t => $trickData) {

            $trick = new Trick();
            $this->initTrick($trick, $trickData);

            $manager->persist($trick);

            $this->addReference('trick' . $t, $trick);
        }

        $manager->flush();
    }


    public function initTrick($trick, $trickData): Trick
    {
        $faker = Factory::create('fr_FR');
        $date = $faker->dateTimeBetween('-2 months');

        $usersData = $this->getReference('user' . mt_rand(1, UserFixture::DATA));
        $categoriesData = $this->getReference('category' . mt_rand(0, count(CategoryFixture::CATEGORY_DATA) - 1));

        $trick
            ->setTitle($trickData['title'])
            ->setDescription($trickData['description'])
            ->setCreatedAt($date)
            ->setUpdatedAt($date)
            ->setUser($usersData)
        ;


        for ($i = 0; $i < mt_rand(2, 6); $i++) {

            $trick->addCategory($categoriesData);


        }
        return $trick;
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixture::class,
            UserFixture::class
        ];
    }
}
