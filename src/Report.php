<?php
namespace MSE;

require_once __DIR__ . '/../config/database.php';

class Report {
    private $db;
    
    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }
    
    /**
     * Sauvegarder un rapport
     */
    public function save($data) {
        $sql = "INSERT INTO reports (
            report_num, report_date, address,
            c1_marque, c1_modele, c1_serie,
            c2_marque, c2_modele, c2_serie,
            etat_general, anomalies, travaux_realises, recommandations,
            urgence, intervenant, mesures, controles, releves
        ) VALUES (
            :report_num, :report_date, :address,
            :c1_marque, :c1_modele, :c1_serie,
            :c2_marque, :c2_modele, :c2_serie,
            :etat_general, :anomalies, :travaux_realises, :recommandations,
            :urgence, :intervenant, :mesures, :controles, :releves
        ) RETURNING id";
        
        $stmt = $this->db->prepare($sql);
        
        $mesuresJson = json_encode($data['mesures'] ?? []);
        $controlesJson = json_encode($data['controles'] ?? []);
        $relevesJson = json_encode($data['releves'] ?? []);
        
        $stmt->execute([
            'report_num' => $data['reportNum'] ?? null,
            'report_date' => $data['reportDate'] ?? null,
            'address' => $data['address'] ?? null,
            'c1_marque' => $data['c1_marque'] ?? null,
            'c1_modele' => $data['c1_modele'] ?? null,
            'c1_serie' => $data['c1_serie'] ?? null,
            'c2_marque' => $data['c2_marque'] ?? null,
            'c2_modele' => $data['c2_modele'] ?? null,
            'c2_serie' => $data['c2_serie'] ?? null,
            'etat_general' => $data['etat_general'] ?? null,
            'anomalies' => $data['anomalies'] ?? null,
            'travaux_realises' => $data['travaux_realises'] ?? null,
            'recommandations' => $data['recommandations'] ?? null,
            'urgence' => $data['urgence'] ?? null,
            'intervenant' => $data['intervenant'] ?? null,
            'mesures' => $mesuresJson,
            'controles' => $controlesJson,
            'releves' => $relevesJson
        ]);
        
        $result = $stmt->fetch();
        return $result['id'];
    }
    
    /**
     * Récupérer tous les rapports
     */
    public function getAll($limit = 100, $offset = 0) {
        $sql = "SELECT 
            id, report_num, report_date, address,
            c1_marque, c1_modele, c1_serie,
            c2_marque, c2_modele, c2_serie,
            etat_general, anomalies, travaux_realises, recommandations,
            urgence, intervenant,
            mesures::text, controles::text, releves::text,
            created_at, updated_at
        FROM reports 
        ORDER BY created_at DESC 
        LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        
        $reports = $stmt->fetchAll();
        
        // Décoder les JSON
        foreach ($reports as &$report) {
            $report['mesures'] = json_decode($report['mesures'], true);
            $report['controles'] = json_decode($report['controles'], true);
            $report['releves'] = json_decode($report['releves'], true);
        }
        
        return $reports;
    }
    
    /**
     * Récupérer un rapport par ID
     */
    public function getById($id) {
        $sql = "SELECT 
            id, report_num, report_date, address,
            c1_marque, c1_modele, c1_serie,
            c2_marque, c2_modele, c2_serie,
            etat_general, anomalies, travaux_realises, recommandations,
            urgence, intervenant,
            mesures::text, controles::text, releves::text,
            created_at, updated_at
        FROM reports 
        WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $report = $stmt->fetch();
        
        if ($report) {
            $report['mesures'] = json_decode($report['mesures'], true);
            $report['controles'] = json_decode($report['controles'], true);
            $report['releves'] = json_decode($report['releves'], true);
        }
        
        return $report;
    }
    
    /**
     * Mettre à jour un rapport
     */
    public function update($id, $data) {
        $sql = "UPDATE reports SET
            report_num = :report_num,
            report_date = :report_date,
            address = :address,
            c1_marque = :c1_marque,
            c1_modele = :c1_modele,
            c1_serie = :c1_serie,
            c2_marque = :c2_marque,
            c2_modele = :c2_modele,
            c2_serie = :c2_serie,
            etat_general = :etat_general,
            anomalies = :anomalies,
            travaux_realises = :travaux_realises,
            recommandations = :recommandations,
            urgence = :urgence,
            intervenant = :intervenant,
            mesures = :mesures,
            controles = :controles,
            releves = :releves,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        $mesuresJson = json_encode($data['mesures'] ?? []);
        $controlesJson = json_encode($data['controles'] ?? []);
        $relevesJson = json_encode($data['releves'] ?? []);
        
        return $stmt->execute([
            'id' => $id,
            'report_num' => $data['reportNum'] ?? null,
            'report_date' => $data['reportDate'] ?? null,
            'address' => $data['address'] ?? null,
            'c1_marque' => $data['c1_marque'] ?? null,
            'c1_modele' => $data['c1_modele'] ?? null,
            'c1_serie' => $data['c1_serie'] ?? null,
            'c2_marque' => $data['c2_marque'] ?? null,
            'c2_modele' => $data['c2_modele'] ?? null,
            'c2_serie' => $data['c2_serie'] ?? null,
            'etat_general' => $data['etat_general'] ?? null,
            'anomalies' => $data['anomalies'] ?? null,
            'travaux_realises' => $data['travaux_realises'] ?? null,
            'recommandations' => $data['recommandations'] ?? null,
            'urgence' => $data['urgence'] ?? null,
            'intervenant' => $data['intervenant'] ?? null,
            'mesures' => $mesuresJson,
            'controles' => $controlesJson,
            'releves' => $relevesJson
        ]);
    }
    
    /**
     * Supprimer un rapport
     */
    public function delete($id) {
        $sql = "DELETE FROM reports WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Rechercher des rapports
     */
    public function search($query, $limit = 50) {
        $sql = "SELECT 
            id, report_num, report_date, address,
            c1_marque, c1_modele, c1_serie,
            c2_marque, c2_modele, c2_serie,
            etat_general, anomalies, travaux_realises, recommandations,
            urgence, intervenant,
            mesures::text, controles::text, releves::text,
            created_at, updated_at
        FROM reports 
        WHERE 
            report_num ILIKE :query OR
            address ILIKE :query OR
            intervenant ILIKE :query OR
            c1_marque ILIKE :query OR
            c2_marque ILIKE :query
        ORDER BY created_at DESC 
        LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':query', "%$query%", \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        $reports = $stmt->fetchAll();
        
        foreach ($reports as &$report) {
            $report['mesures'] = json_decode($report['mesures'], true);
            $report['controles'] = json_decode($report['controles'], true);
            $report['releves'] = json_decode($report['releves'], true);
        }
        
        return $reports;
    }
    
    /**
     * Compter le nombre total de rapports
     */
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM reports";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
}
