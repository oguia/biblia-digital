<?php

class Company extends Model {
    protected $table = 'companies';

    public function search($query, $category = null, $neighborhood = null, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT c.*, cat.name as category_name, n.name as neighborhood_name
                FROM companies c
                LEFT JOIN categories cat ON c.category_id = cat.id
                LEFT JOIN neighborhoods n ON c.neighborhood_id = n.id
                WHERE c.status = 'active'";

        $params = [];

        if (!empty($query)) {
            $sql .= " AND (c.name LIKE :query OR c.description LIKE :query)";
            $params['query'] = "%$query%";
        }

        if (!empty($category)) {
            $sql .= " AND cat.slug = :category";
            $params['category'] = $category;
        }

        if (!empty($neighborhood)) {
            $sql .= " AND n.slug = :neighborhood";
            $params['neighborhood'] = $neighborhood;
        }

        $sql .= " ORDER BY c.is_featured DESC, c.name ASC LIMIT $limit OFFSET $offset";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getBySlug($slug) {
        $stmt = $this->db->prepare("
            SELECT c.*, cat.name as category_name, n.name as neighborhood_name
            FROM companies c
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN neighborhoods n ON c.neighborhood_id = n.id
            WHERE c.slug = :slug AND c.status = 'active'
        ");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }

    public function getFeatured($limit = 6) {
        $driver = $this->db->getAttribute(PDO::ATTR_DRIVER_NAME);
        $randFunc = ($driver === 'sqlite') ? 'RANDOM()' : 'RAND()';

        $sql = "SELECT c.*, cat.name as category_name, n.name as neighborhood_name
                FROM companies c
                LEFT JOIN categories cat ON c.category_id = cat.id
                LEFT JOIN neighborhoods n ON c.neighborhood_id = n.id
                WHERE c.status = 'active' AND c.is_featured = 1
                ORDER BY $randFunc
                LIMIT $limit";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
