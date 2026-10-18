# Boutique — e-commerce algérien (Laravel)

Boutique en ligne pensée pour le marché algérien : **paiement à la livraison**, **livraison dans les 58 wilayas** (à domicile ou stop desk) et **tableau de bord** pour gérer les commandes par téléphone.

Projet réalisé par **Tinhinane Amirat** — [Slide & Web](https://slideandweb.netlify.app).

## Fonctionnalités

**Côté client**
- Catalogue et page produit (arabe RTL / français)
- Commande sans compte : nom, téléphone, wilaya, commune
- Frais de livraison calculés en direct selon la wilaya et le type (domicile / stop desk)
- Page de confirmation protégée par un lien signé

**Tableau de bord admin**
- Connexion sécurisée (limite de tentatives)
- Commandes : filtres par statut et wilaya, statuts *Nouveau → Confirmé → Expédié → Livré / Annulé*, bouton d'appel direct
- Produits : ajout, modification, suppression, image, masquage, gestion du stock
- Livraison : tarifs des 58 wilayas modifiables
- Statistiques : commandes du jour / du mois, chiffre d'affaires, top produits, top wilayas

## Stack

Laravel 13 · PHP 8.5 · Blade · Tailwind CSS 4 · Alpine.js · SQLite (compatible MySQL)

## Points techniques

- **Prix recalculés côté serveur** : le total envoyé par le navigateur n'est jamais utilisé.
- **Transactions** : la commande et la baisse du stock sont enregistrées ensemble ou pas du tout ; une annulation remet le stock.
- **Prix figés dans la commande** : modifier un prix ne change pas les anciennes commandes.
- **Localisation** : `lang/fr.json` + middleware `SetLocale`, direction RTL/LTR automatique.
- **Sécurité** : CSRF, validation, mots de passe hachés, lien signé, `throttle` sur la connexion.

## Installation

```bash
git clone <url-du-repo> boutique && cd boutique
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
npm install && npm run build
php artisan serve
```

Admin de démo : `admin@boutique.test` / `password` (à changer en production).
