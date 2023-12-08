<?php

namespace App\Repository;

use App\AppRepoManager;
use App\Model\Pizza;
use Core\Repository\Repository;

class PizzaRepository extends Repository
{
    public function getTableName(): string
    {
        return 'pizza';
    }

    //on crée une méthode qui va récupérer toutes les pizzas
    public function getAllPizzas(): array
    {
        //on déclare un tableau vide
        $array_result = [];

        //on déclare la requete SQL
        $query = sprintf(
            'SELECT `id`, `name`, `image_path` 
            FROM %s 
            WHERE `is_active`=1 
            AND `user_id`=1',
            $this->getTableName()
        );

        //on peut directement executer la requete avec la méthode query()
        $stmt = $this->pdo->query($query);
        //on vérifie si la requete s'est bien exécutée
        if (!$stmt) return $array_result;

        //on récupère les données de la table dans une boucle
        while ($row_data = $stmt->fetch()) {
            $array_result[] = new Pizza($row_data);
        }

        return $array_result;
    }

    //on crée une méthode qui va récupérer une pizza par son id
    public function getPizzaById(int $pizza_id): ?Pizza
    {
        //on crée la requete
        $query = sprintf(
            'SELECT * FROM %s WHERE `id`=:id',
            $this->getTableName()
        );

        //on prépare la requete
        $stmt = $this->pdo->prepare($query);

        //on vérifie si la requete s'est bien préparée
        if (!$stmt) return null;

        //on execute la requete en bindant les paramètres
        $stmt->execute(['id' => $pizza_id]);

        //on recupère le resultat
        $result = $stmt->fetch();

        //si je n'ai pas de résultat on retourne null
        if (!$result) return null;

        //on retourne une nouvelle instance de Pizza
        $pizza = new Pizza($result);

        //on va hydrater les ingrédients de la pizza
        $pizza->ingredients = AppRepoManager::getRm()->getPizzaIngredientRepository()->getIngredientByPizzaId($pizza->id);
        //on va hydrater les prix de la pizza
        $pizza->prices = AppRepoManager::getRm()->getPriceRepository()->getPriceByPizzaId($pizza->id);

        return $pizza;
    }
}
