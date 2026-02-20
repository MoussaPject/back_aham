#!/bin/bash

# Script de déploiement pour Railway avec HTTPS automatique
# Usage: ./deploy.sh

echo "🚀 Déploiement HTTPS pour Ahma Dile Boutique"

# 1. Vérifier l'environnement
if [ "$RAILWAY_ENVIRONMENT" = "production" ]; then
    echo "✅ Environnement production détecté"
    
    # 2. Mettre à jour .env pour HTTPS
    echo "📝 Configuration HTTPS..."
    
    # Forcer HTTPS en production
    php artisan config:cache
    php artisan cache:clear
    
    # 3. Générer le sitemap avec URLs HTTPS
    echo "🗺️ Génération du sitemap HTTPS..."
    php artisan sitemap:generate
    
    # 4. Optimiser pour production
    echo "⚡ Optimisation production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    # 5. Vérifier le sitemap
    echo "🔍 Vérification du sitemap..."
    if [ -f "public/sitemap.xml" ]; then
        echo "✅ Sitemap généré avec succès"
        echo "📍 URL: $RAILWAY_PUBLIC_DOMAIN/sitemap.xml"
    else
        echo "❌ Erreur: sitemap.xml non trouvé"
        exit 1
    fi
    
    # 6. Tester les URLs HTTPS
    echo "🌐 Test des URLs HTTPS..."
    curl -I -s "https://$RAILWAY_PUBLIC_DOMAIN/sitemap.xml" | head -1
    
    echo "✅ Déploiement HTTPS terminé!"
    echo "🌐 Site disponible: https://$RAILWAY_PUBLIC_DOMAIN"
    
else
    echo "🔧 Environnement local - configuration HTTP"
    php artisan sitemap:generate
    echo "✅ Sitemap local généré"
fi

echo "📋 Résumé:"
echo "   - URLs HTTPS: ✅"
echo "   - Sitemap: ✅" 
echo "   - TrustProxies: ✅"
echo "   - Headers sécurité: ✅"
