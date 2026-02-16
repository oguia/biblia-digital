<?php

class Category extends Model {
    protected $table = 'categories';

    public function getPopular($limit = 10) {
        $sql = "SELECT c.*, COUNT(co.id) as company_count
                FROM categories c
                LEFT JOIN companies co ON c.id = co.category_id
                GROUP BY c.id
                ORDER BY company_count DESC, c.views DESC
                LIMIT $limit";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
