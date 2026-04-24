<?php

$pageTitle = isset($pageTitle) ? $pageTitle : 'CRUD Usuarios';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        :root {
            color-scheme: light;
        }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f6f3ee;
            color: #1f2937;
        }
        .shell {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px;
        }
        .panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.06);
            padding: 24px;
        }
        .brand {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }
        a { color: #0f766e; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .message, .success, .error-box {
            padding: 12px 14px;
            border-radius: 12px;
            margin: 12px 0;
        }
        .message { background: #fff7ed; color: #9a3412; }
        .success { background: #ecfdf5; color: #166534; }
        .error-box { background: #fef2f2; color: #991b1b; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        input, select, button, textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font: inherit;
        }
        button {
            background: #0f766e;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover { background: #115e59; }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .muted { color: #6b7280; }
        @media (max-width: 720px) {
            .grid { grid-template-columns: 1fr; }
            .brand { flex-direction: column; align-items: flex-start; gap: 8px; }
        }
    </style>
</head>
<body>
<div class="shell">
