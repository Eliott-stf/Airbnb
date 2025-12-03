# Changelog

Tous les changements notables de ce projet seront documentés dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/),
et ce projet adhère à [Semantic Versioning](https://semver.org/lang/fr/).

## [1.0.2] - 2024-11-24

### Corrigé
- Correction du séparateur de préfixe dans AbstractCacheDriver : utilisation de `_` au lieu de `:` pour respecter la validation des clés
- Les clés avec préfixe utilisent maintenant le format `prefix_key` au lieu de `prefix:key`

## [1.0.1] - 2024-11-24

### Corrigé
- Correction du système de tags : remplacement du séparateur `:` par `_` pour respecter la validation des clés
- Les clés taguées utilisent maintenant le format `tagged_hash_key` au lieu de `tagged:hash:key`
- Correction de la validation des clés pour éviter les erreurs avec les tags

## [1.0.0] - 2024-11-24

### Ajouté
- Système de cache complet avec support de multiples drivers
- Driver Array (cache en mémoire)
- Driver File (cache sur disque)
- Driver Redis (nécessite l'extension Redis)
- Système de tags pour invalidation groupée
- Support TTL (Time To Live) avec expiration automatique
- Validation sécurisée des clés de cache
- Sérialisation sécurisée avec JSON
- Opérations multiples (getMultiple, setMultiple, deleteMultiple)
- Incrémentation et décrémentation de valeurs numériques
- Méthode pull() pour récupérer et supprimer en une opération
- Interface fluide avec façade statique Cache
- CacheManager pour gestion avancée
- TaggedCache pour gestion des tags
- Tests unitaires complets
- Documentation complète avec exemples

### Sécurité
- Validation stricte des clés (protection contre les injections de chemins)
- Sérialisation sécurisée avec validation JSON
- Permissions de fichiers contrôlées pour le driver File
- Écriture atomique pour éviter la corruption des données
- Protection contre les caractères spéciaux dans les clés

