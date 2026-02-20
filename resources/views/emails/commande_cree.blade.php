<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande - Ahma-Dile Boutique</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .logo {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .tagline {
            font-size: 1.1em;
            opacity: 0.9;
            font-style: italic;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 1.8em;
            color: #1f2937;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .status-info {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .status-icon {
            font-size: 2em;
            margin-right: 15px;
            background: rgba(255, 255, 255, 0.2);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .order-info {
            background: #f8fafc;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }

        .order-date {
            display: flex;
            align-items: center;
            color: #64748b;
            font-size: 1.1em;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 1.3em;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }

        .items-list {
            list-style: none;
        }

        .items-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            background: white;
            border-radius: 10px;
            border-left: 4px solid #4f46e5;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .items-list li:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 1.1em;
            margin-bottom: 5px;
        }

        .item-details {
            color: #64748b;
            font-size: 0.9em;
        }

        .item-price {
            font-weight: bold;
            color: #4f46e5;
            font-size: 1.1em;
        }

        .total-section {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 25px;
            box-shadow: 0 8px 25px rgba(30, 41, 59, 0.3);
        }

        .total-amount {
            font-size: 2.2em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .total-label {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 30px 0;
        }

        .tracking-info {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 8px 25px rgba(251, 191, 36, 0.3);
        }

        .tracking-info p {
            font-size: 1.1em;
            margin: 0;
        }

        .footer {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .footer-message {
            font-size: 1.1em;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .company-name {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .contact-info {
            font-size: 1em;
            opacity: 0.9;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .contact-info span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 15px;
            }

            .header {
                padding: 30px 20px;
            }

            .logo {
                font-size: 2em;
            }

            .content {
                padding: 30px 20px;
            }

            .greeting {
                font-size: 1.5em;
            }

            .items-list li {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .item-price {
                align-self: flex-end;
            }

            .contact-info {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        <div class="logo">Ahma-Dile Boutique</div>
        <div class="tagline">Votre style, notre passion</div>
    </div>

    <div class="content">
        {{-- Salutation avec le vrai nom de l'utilisateur si disponible --}}
        <h1 class="greeting">
            Bonjour {{ optional($commande->user)->name ?? 'cher client' }},
        </h1>

        {{-- Bloc statut --}}
        <div class="status-info">
            <div class="status-icon">✓</div>
            <div>
                <strong>Votre commande a été confirmée !</strong><br>
                Merci pour votre confiance. Votre commande est en cours de préparation.
            </div>
        </div>

        {{-- Informations principales commande --}}
        <div class="order-info">
            <div class="order-date">
                📅 Commande passée le
                {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y à H:i') }}<br>
                🔢 Référence : <strong>{{ $commande->reference ?? ('CMD-' . $commande->id) }}</strong><br>
                🧾 Statut : <strong>{{ ucfirst($commande->statut) }}</strong>
            </div>

            <div class="section-title">Détails de votre commande</div>

            <ul class="items-list">
                @foreach($commande->items as $item)
                    <li>
                        <div class="item-info">
                            <div class="item-name">
                                {{ $item->produit->nom ?? 'Produit' }}
                            </div>
                            <div class="item-details">
                                @php
                                    $unite = $item->produit->unite ?? '';
                                    $prixUnitaire = number_format($item->prix_unitaire, 0, ',', ' ');
                                    $sousTotal = number_format($item->prix_unitaire * $item->quantite, 0, ',', ' ');
                                @endphp
                                Quantité : {{ $item->quantite }} {{ $unite }} × {{ $prixUnitaire }} FCFA
                            </div>
                        </div>
                        <div class="item-price">
                            {{ $sousTotal }} FCFA
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Total de la commande --}}
        @php
            $total = number_format($commande->total, 0, ',', ' ');
        @endphp
        <div class="total-section">
            <div class="total-amount">{{ $total }} FCFA</div>
            <div class="total-label">Montant total à régler à la livraison</div>
        </div>

        <div class="divider"></div>

        {{-- Informations de livraison --}}
        @if($commande->livraison)
            <div class="tracking-info">
                <p>
                    📦 Livraison prévue pour :<br>
                    <strong>{{ $commande->livraison->nom_client }}</strong><br>
                    📍 {{ $commande->livraison->adresse }}, {{ $commande->livraison->ville }}<br>
                    📞 {{ $commande->livraison->telephone }}
                </p>
                @if($commande->livraison->instructions)
                    <p style="margin-top:10px;font-size:0.9em;">
                        🔎 Instructions : {{ $commande->livraison->instructions }}
                    </p>
                @endif
            </div>
        @else
            <div class="tracking-info">
                <p>📦 Nous vous tiendrons informé de l'évolution de votre commande par email et SMS.</p>
            </div>
        @endif
    </div>

    <div class="footer">
        <p class="footer-message">
            Merci de faire confiance à notre boutique. Nous sommes ravis de vous compter parmi nos clients privilégiés.
        </p>
        <div class="company-name">Ahma-Dile Boutique</div>
        <div class="contact-info">
            <span>📧 contact@ahma-dile.com</span>
            <span>📞 +221 XX XXX XX XX</span>
        </div>
    </div>
</div>
</body>
</html>
