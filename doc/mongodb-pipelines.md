# Pipelines d'agregació MongoDB

## Pipeline 1

Aquest pipeline permet obtenir les 5 URLs més repetides d’una col·lecció.

## Codi

```php
$pipeline = [
    ['$group' => ['_id' => '$url', 'count' => ['$sum' => 1]]],
    ['$sort' => ['count' => -1]],
    ['$limit' => 5]
];
```

# $group

Agrupa els documents pel camp de url i compta quants documents existeixen per cada URL.

```php
['$group' => ['_id' => '$url', 'count' => ['$sum' => 1]]]
```

# $sort

Ordena els resultats per el camp count en ordre descendent.

```php
    ['$sort' => ['count' => -1]],
```

# $limit

Limita a mostrar només els 5 primers resultats.

```php
    ['$limit' => 5]
```