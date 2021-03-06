<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Entreprise;
use App\Entity\Formation;
use App\Entity\Stage;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //création de 2 USER
        $greg = new User();
        $greg->setPrenom("Grégory");
        $greg->setNom("Errecart");
        $greg->setEmail("greg@mail.com");
        $greg->setRoles(['ROLE_USER','ROLE_ADMIN']);
        $greg->setPassword("$2y$10$1Due5BiWjUC/9JQSbXdWKOQnCgCF8dhR2K7by6xROnTYgP2lnMVNe");
        $manager->persist($greg);

        $nathan = new User();
        $nathan->setPrenom("Nathan");
        $nathan->setNom("Delcambre");
        $nathan->setEmail("nathan@mail.com");
        $nathan->setRoles(['ROLE_USER']);
        $nathan->setPassword("$2y$10$4tWehIaiO841EitA2mxfj.x5onHazL233KPUDRfvkrBoUrQjg1cCC");
        $manager->persist($nathan);
        //Création d'un générateur de données faker
        $faker = \Faker\Factory::create('fr_FR');

        //GESTION DU NOMBRE D'ENTITE
        $nbEntreprises = 20;
        $nbFormations = 20;
        $nbStages = 30;


        /* GESTION DES ENTREPRISES */

        //Gestion du tableau 
        $tabEntreprises = array();

        //Mise en place des données
        $activites = array(
            "Agroalimentaire", "Banque / Assurance", "Bois / Papier / Carton / Imprimerie", "BTP / Matériaux de construction", "Chimie / Parachimie",
            "Commerce / Négoce / Distribution", "Édition / Communication / Multimédia", "Électronique / Électricité", "Études et conseils",
            "Industrie pharmaceutique", "Informatique / Télécoms", "Machines et équipements / Automobile", "Métallurgie / Travail du métal",
            "Plastique / Caoutchouc", "Services aux entreprises", "Textile / Habillement / Chaussure", "Transports / Logistique"
        );

        $nomEntreprises = array(
            "Caratch", "Caromni", "Stepegg", "Caraipi", "Netelectra", "Cafesea", "Cafefire", "Woodtap", "Reelectra", "Cafejar", "Cafemirror", "Electra",
            "The Car Group", "Bestofstep", "Enginecafe", "Nuelectra", "Carer", "Softdude", "Woodcell", "Targetwood"
        );

        //Boucle création des entreprises
        for ($i = 1; $i <= $nbEntreprises; $i++) {
            $entreprise = new Entreprise();

            $entreprise->setActivite($activites[$faker->numberBetween(0, count($activites) - 1)]);

            $entreprise->setAdresse($faker->address());

            $numeroEntreprise = $faker->numberBetween(0, count($nomEntreprises) - 1);
            $nomChoisi = $nomEntreprises[$numeroEntreprise];
            unset($nomEntreprises[$numeroEntreprise]);
            $nomEntreprises = array_values($nomEntreprises);

            $entreprise->setNom($nomChoisi);
            $entreprise->setUrlSite($faker->url());

            array_push($tabEntreprises, $entreprise);

            $manager->persist($entreprise);
        }


        /* GESTION DES FORMATIONS */

        //Gestion du tableau
        $tabFormations = array();

        //Mise en place des données
        $diplomePossible = array("Diplôme Universitaire Technologique", "Brevet Technicien Supérieur", "Ingénieur",
                "Master", "Prepa", "Baccalauréat", "License", "LP", "Certificat Aptitude Professionnelle", "Bachelor Universitaire Technologique"
        );
        $specialiteDisponible = array("Informatique", "Mathématiques", "Statistiques", "Droit", "Economie", "Ressources Humaines", "Numérique", "Robotique"
        );

        //Boucle création des formations
        for ($i = 0; $i < $nbFormations; $i++) {
            $formation = new Formation();

            $diplome = $diplomePossible[$faker->numberBetween(0, count($diplomePossible) - 1)];
            $nomCourtDiplome = $diplome;
            
            $tabMotsDiplome = explode(" ", $diplome);
        
            if (count($tabMotsDiplome) != 1) {
                $nomCourtDiplome = ' ';
                for ($j = 0; $j < count($tabMotsDiplome); $j++) {
                    $lettres = str_split($tabMotsDiplome[$j]);
                    $nomCourtDiplome = $nomCourtDiplome . $lettres[0];
                }
            }
            //$formation=$nomLongFormation;
            $nomFormationCourt = $nomCourtDiplome . " " . $specialiteDisponible[$faker->numberBetween(0, count($specialiteDisponible) - 1)];
            $nomLongFormation = $diplome . " " .$specialiteDisponible[$faker->numberBetween(0, count($specialiteDisponible) - 1)];
        
            $formation->setNomLong($nomLongFormation);
            $formation->setNomCourt($nomFormationCourt);

            array_push($tabFormations, $formation);

            $manager->persist($formation);
        }


        /* GESTION DES STAGES */

        //Boucle création des stages
        for ($i = 1; $i <= $nbStages; $i++) {
            $stage = new Stage();

            $debutTitre = array("Création", "Gestion", "Études", "Élaboration", "Opérations", "Informatisation", "Mise à jour", "Construction", "Attribution", "Organisation", "Réalisation");
            $finTitre = array("base de données", "site Web", "stock", "l'organisation logistique", "l'administration", "la stratégie de communication de l'entreprise");
            $langage = array("JavaScript", "Python", "Java", "Go", "Ruby", "TypeScript", "C++", "PHP", "C#", "C");

            $stage->setTitre($debutTitre[$faker->numberBetween(0, count($debutTitre) - 1)] . " de " . $finTitre[$faker->numberBetween(0, count($finTitre) - 1)] . " en " . $langage[$faker->numberBetween(0, count($langage) - 1)]);
            $stage->setDescription($faker->realText(200));
            $stage->setEmail($faker->freeEmail());

            $stage->setEntreprise($tabEntreprises[$faker->numberBetween(0, count($tabEntreprises) - 1)]);

            $nbFormationStage = $faker->numberBetween(1, 2);

            for ($j = 0; $j < $nbFormationStage; $j++) {
                $stage->addFormation($tabFormations[$faker->numberBetween(0, count($tabFormations) - 1)]);
            }

            $manager->persist($stage);
        }

        $manager->flush();
    }
}
