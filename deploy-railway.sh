#!/bin/bash

# Script de déploiement pour Railway avec sitemap.xml
# Usage: ./deploy-railway.sh

echo "🚀 Déploiement Railway pour Ahma Dile Boutique"

# 1. Générer le sitemap avec URLs Railway
echo "📝 Génération du sitemap HTTPS..."
php artisan sitemap:generate

# 2. Vérifier que le fichier existe
if [ -f "public/sitemap.xml" ]; then
    echo "✅ Sitemap généré avec succès"
    echo "📍 Contenu généré pour: https://backaham-production.up.railway.app/sitemap.xml"
else
    echo "❌ Erreur: sitemap.xml non généré"
    exit 1
fi

# 3. Vérifier le contenu du sitemap
echo "🔍 Vérification du sitemap..."
head -10 public/sitemap.xml

# 4. Optimiser pour production
echo "⚡ Optimisation production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Nettoyer le cache local
echo "🧹 Nettoyage cache local..."
php artisan cache:clear

echo "✅ Déploiement prêt pour Railway!"
echo ""
echo "📋 Fichiers générés:"
echo "   - public/sitemap.xml (URLs Railway)"
echo "   - public/robots.txt (référence sitemap)"
echo ""
echo "🌐 URLs de production:"
echo "   - Sitemap: https://backaham-production.up.railway.app/sitemap.xml"
echo "   - Robots: https://backaham-production.up.railway.app/robots.txt"
echo ""
echo "🚀 Déployez maintenant sur Railway avec ces fichiers dans public/"
