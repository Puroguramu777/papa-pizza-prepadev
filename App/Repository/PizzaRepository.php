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
            'SELECT p.`id`, p.`name`, p.`image_path` 
            FROM %1$s AS p
            INNER JOIN %2$s AS u ON p.`user_id` = u.`id`
            WHERE p.`is_active`=1 
            AND u.`is_admin`=1',
            $this->getTableName(),
            AppRepoManager::getRm()->getUserRepository()->getTableName()
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

    public function getAllPizzasWithInfo(): array
    {
        //on déclare un tableau vide
        $array_result = [];

        //on déclare la requete SQL
        $query = sprintf(
            'SELECT p.`id`, p.`name`, p.`image_path` 
            FROM %1$s AS p
            INNER JOIN %2$s AS u ON p.`user_id` = u.`id`
            WHERE p.`is_active`=1 
            AND u.`is_admin`=1',
            $this->getTableName(),
            AppRepoManager::getRm()->getUserRepository()->getTableName()
        );

        //on peut directement executer la requete avec la méthode query()
        $stmt = $this->pdo->query($query);
        //on vérifie si la requete s'est bien exécutée
        if (!$stmt) return $array_result;

        //on récupère les données de la table dans une boucle
        while ($row_data = $stmt->fetch()) {
            $pizza = new Pizza($row_data);

            $pizza->ingredients = AppRepoManager::getRm()->getPizzaIngredientRepository()->getIngredientByPizzaId($pizza->id);
            $pizza->prices = AppRepoManager::getRm()->getPriceRepository()->getPriceByPizzaId($pizza->id);

            $array_result[] = $pizza;


        }

        return $array_result;
    }

    public function insertPizza(array $data): ?Pizza
    {
        $query = sprintf(
            'INSERT INTO %s (name, image_path, is_active, user_id)
            VALUES (:name, :image_path, :is_active, :user_id)',
            $this->getTableName()
        );

        $stmt = $this->pdo->prepare($query);

        if(!$stmt) return null;
        $stmt->execute($data);

        $pizza_id = $this->pdo->lastInsertId();

        return $this->getPizzaById($pizza_id);
    }

    public function deletePizza(int $id): bool
    {
        $query = sprintf(
            'UPDATE %s SET is_active = 0 WHERE id = :id',
            $this->getTableName()
        );

        $stmt = $this->pdo->prepare($query);

        if (!$stmt) return false;

        return $stmt->execute(['id' => $id]);
    }

    public function getUsersPizzas(int $id): array
    {
        //on déclare un tableau vide
        $array_result = [];
        
        //on déclare la requete SQL
        $query = sprintf(
            'SELECT p.`id`, p.`name`, p.`image_path` 
            FROM %s AS p
            WHERE p.`is_active`=1 
            AND p.user_id = %s',
            $this->getTableName(),
            $id

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

    public function addCartPizza(int $id): bool
    {
        $query = sprintf(
            'UPDATE %s SET is_cart = 1 WHERE id = :id',
            $this->getTableName()
        );

        $stmt = $this->pdo->prepare($query);

        if (!$stmt) return false;

        return $stmt->execute(['id' => $id]);
    }

    public function UpdateUserForm(array $data)
    {
        
        $name = $_POST['first'];
        $query = sprintf(
            'UPDATE user SET firstname ="'. $name . '"'
        );
        $stmt = $this->pdo->prepare($query);

        if (!$stmt) return false;

        return $stmt->execute([]);


       


    }
    
    
    


}
