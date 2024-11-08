<?php

declare(strict_types=1);

class AdminPanelService {

    private static int $page = 1;
    private static int $limit = 10;
    private static int $offset;
    private static string $sortAttribute = "user_name";
    private static string $sortByDescendingOrAscending = "DESC";

    public static function getResponses(?array $data) :array
    {
        try {

            $dtoResponses = [];

            if(isset($data['page']) && !empty($data['page'])){
                self::$page = intval($data['page']);
            }
            else {
                self::$page = 1;
            }

            self::$offset =  self::$limit * (self::$page - 1);

            if(isset($data['sort_attribute']) && !empty($data['sort_attribute'])){
                self::$sortAttribute = $data['sort_attribute'];
            }
            else {
                self::$sortAttribute = "user_name";
            }

            if(isset($data['sort_by_descending_or_ascending']) && !empty($data['sort_by_descending_or_ascending'])){
                self::$sortByDescendingOrAscending = $data['sort_by_descending_or_ascending'];
            }
            else {
                self::$sortByDescendingOrAscending = "DESC";
            }

            if(self::$sortAttribute == "user_name" ||
                self::$sortAttribute == "email" ||
                self::$sortAttribute == "date_of_writing")
            {
                if(self::$sortByDescendingOrAscending == "DESC" ||
                    self::$sortByDescendingOrAscending == "ASC")
                {
                    if(self::$limit > 0) {
                        if(self::$offset >= 0) {
                            $factoryDAO = DAOFactoryImpl::getInstance();
                            $responseDAO = $factoryDAO::getResponseDAO();
                            $dtoResponses = self::buildResponsesDTO($responseDAO->getAllForAdminPanel(
                               limit: self::$limit,
                               offset: self::$offset,
                               sortAttribute: self::$sortAttribute,
                               sortByDescendingOrAscending: self::$sortByDescendingOrAscending
                            ));
                        }
                    }
                }
            }
            return $dtoResponses;
        } catch (Exception $ex){
            return [];
        }
    }

    private static function buildResponsesDTO(array $entityResponses) :array{
        $dtoResponses = [];
        foreach ($entityResponses as $response){
            $dtoResponses[] = new ResponseDTO($response);
        }
        return $dtoResponses;
    }

    public static function getNumberOfResponses() :int {
        $factoryDAO = DAOFactoryImpl::getInstance();
        $responseDAO = $factoryDAO::getResponseDAO();
        return $responseDAO->getNumber();
    }


    /**
     * @return int
     */
    public static function getPage(): int
    {
        return self::$page;
    }

    /**
     * @return int
     */
    public static function getLimit(): int
    {
        return self::$limit;
    }

    /**
     * @return string
     */
    public static function getSortAttribute(): string
    {
        return self::$sortAttribute;
    }

    /**
     * @return string
     */
    public static function getSortByDescendingOrAscending(): string
    {
        return self::$sortByDescendingOrAscending;
    }

}