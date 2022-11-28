<?php
    $queries = array();

    class Queries {

        function getQuery($class, $interval){
            switch ($class) {
                case 'home':
                    return getHomeQuery($interval);
                    break;
                
                default:
                    # do nothing
                    break;
            }
        }

        function getHomeQuery($interval){
            global $queries;
            switch ($intervalvariable) {
                case '1h':
                    return $queries["home_per_hour"];
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    
    
    
    }

    $queries["home_per_hour"] = '
                SELECT
                    "collection",
                    "hour",
                    COUNT(*) OVER "win1" AS "volume",
                    AVG(CASE WHEN ("avg_value_perminute" != 0.0) THEN ("avg_value_perminute") END) OVER "win1" AS "avg_value_perhour",
                    SUM("avg_value_perminute") OVER "win1" AS "tot_value_perhour",
                    "floor_price",
                    "market_cap",
                    "count_owners",
                    "collection_name"
                FROM (
                    SELECT *, DATE_TRUNC(\'hour\', "minute") AS "hour"
                    FROM (
                    SELECT
                        "LHS"."collection" AS "collection",
                        "minute",
                        "volume",
                        "avg_value_perminute",
                        "tot_value_perminute",
                        "collection_name",
                        "floor_price",
                        "market_cap",
                        "count_owners"
                    FROM "minute_aggregate" AS "LHS"
                    INNER JOIN (
                        SELECT "collection", "floor_price", "market_cap", "count_owners"
                        FROM "time_indep_aggregate"
                    ) "RHS"
                        ON ("LHS"."collection" = "RHS"."collection")
                    ) "q01"
                ) "q02"
                WINDOW "win1" AS (PARTITION BY "collection", "hour")
        ';
?>