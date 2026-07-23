@extends('shared.layouts.backoffice')

@section('title', 'Liens & Dépendances')
@section('breadcrumb', 'Système / Liens & Dépendances')

@push('styles')
<style>
    .rel-page { padding: 1.5rem 2rem; }

    .rel-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .rel-header-title { font-size: 1.3rem; font-weight: 700; color: #fff; }
    .rel-header-sub { font-size: 0.85rem; color: rgba(255,255,255,0.45); margin-top: 0.2rem; }

    .rel-legend {
        display: flex;
        gap: 1.25rem;
        flex-wrap: wrap;
        font-size: 0.78rem;
        color: rgba(255,255,255,0.55);
    }
    .rel-legend-item { display: flex; align-items: center; gap: 0.4rem; }
    .rel-legend-dot { width: 10px; height: 10px; border-radius: 3px; display: inline-block; }

    .rel-stats-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .rel-stat-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: 0.9rem 1.1rem;
    }
    .rel-stat-card.warn { border-color: rgba(248,113,113,0.4); background: rgba(248,113,113,0.06); }
    .rel-stat-num { font-size: 1.6rem; font-weight: 700; color: #fff; line-height: 1; }
    .rel-stat-lbl { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(255,255,255,0.4); margin-top: 0.25rem; }

    .rel-toolbar {
        display: flex;
        gap: 0.6rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }
    .rel-search {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: #fff;
        padding: 0.5rem 0.8rem;
        font-size: 0.85rem;
        min-width: 220px;
    }
    .rel-toggle-btn {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: rgba(255,255,255,0.7);
        padding: 0.5rem 0.9rem;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.15s;
    }
    .rel-toggle-btn.active { background: rgba(79,156,249,0.18); border-color: #4f9cf9; color: #fff; }
    .rel-toggle-btn:hover { border-color: rgba(255,255,255,0.25); }

    /* View toggle pills */
    .rel-view-toggle {
        display: flex;
        gap: 0.3rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px;
        padding: 0.25rem;
        margin-left: auto;
    }
    .rel-view-btn {
        background: transparent;
        border: none;
        border-radius: 8px;
        padding: 0.4rem 0.9rem;
        font-size: 0.78rem;
        color: rgba(255,255,255,0.5);
        cursor: pointer;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .rel-view-btn:hover { color: rgba(255,255,255,0.8); }
    .rel-view-btn.active {
        background: rgba(79,156,249,0.18);
        color: #4f9cf9;
    }

    /* ─────────────────── FOCUS MODE BANNER ─────────────────── */
    .rel-focus-banner {
        display: none;
        align-items: center;
        gap: 0.75rem;
        padding: 0.55rem 1rem;
        background: rgba(79,156,249,0.1);
        border: 1px solid rgba(79,156,249,0.3);
        border-radius: 10px;
        margin-bottom: 0.75rem;
        font-size: 0.82rem;
        color: rgba(255,255,255,0.75);
    }
    .rel-focus-banner.visible { display: flex; }
    .rel-focus-name {
        font-weight: 700;
        color: #fff;
    }
    .rel-focus-clear {
        margin-left: auto;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 6px;
        color: rgba(255,255,255,0.6);
        padding: 0.2rem 0.7rem;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.15s;
    }
    .rel-focus-clear:hover { background: rgba(248,113,113,0.2); border-color: #f87171; color: #f87171; }

    /* ─────────────────── GRAPH WRAP ─────────────────── */
    .rel-graph-wrap {
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 16px;
        padding: 1rem;
        overflow-x: auto;
        overflow-y: auto;
        min-height: 60vh;
        position: relative;
    }
    .rel-graph-wrap svg { display: block; min-width: 100%; }
    .rel-graph-wrap.loading { display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.4); font-size: 0.9rem; }
    .rel-empty { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0.6rem; color: rgba(255,255,255,0.4); padding: 3rem; }

    /* Dimmed node class */
    .rel-node.dimmed rect { opacity: 0.12 !important; stroke-opacity: 0.15 !important; }
    .rel-node.dimmed text { opacity: 0.12 !important; }
    .rel-node.focused rect { filter: drop-shadow(0 0 8px currentColor); }

    /* ─────────────────── TABLE VIEW ─────────────────── */
    .rel-table-wrap {
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 16px;
        padding: 1rem;
        display: none;
        min-height: 60vh;
    }
    .rel-table-wrap.visible { display: block; }

    .rel-table-section { margin-bottom: 2rem; }
    .rel-table-section-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.4);
        padding: 0.75rem 0 0.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.06);
        margin-bottom: 0.75rem;
    }
    .rel-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
    }
    .rel-table thead th {
        text-align: left;
        padding: 0.5rem 0.75rem;
        font-weight: 600;
        color: rgba(255,255,255,0.4);
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .rel-table tbody td {
        padding: 0.6rem 0.75rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.8);
    }
    .rel-table tbody tr { cursor: pointer; transition: background 0.12s; }
    .rel-table tbody tr:hover { background: rgba(255,255,255,0.04); }
    .rel-table .type-badge {
        display: inline-block;
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .type-badge.workflow { background: rgba(79,156,249,0.15); color: #4f9cf9; }
    .type-badge.form { background: rgba(74,222,128,0.15); color: #4ade80; }
    .type-badge.pdf { background: rgba(244,114,182,0.15); color: #f472b6; }
    .type-badge.orphan { background: rgba(248,113,113,0.15); color: #f87171; }

    .rel-link-chip {
        display: inline-block;
        padding: 0.1rem 0.5rem;
        margin: 0.1rem 0.2rem;
        background: rgba(255,255,255,0.05);
        border-radius: 4px;
        font-size: 0.7rem;
        color: rgba(255,255,255,0.6);
        border: 1px solid rgba(255,255,255,0.06);
    }

    /* ─────────────────── PIPELINE (CARD) VIEW ─────────────────── */
    .rel-pipeline-wrap {
        display: none;
        min-height: 60vh;
    }
    .rel-pipeline-wrap.visible { display: block; }

    .rel-pipeline-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .rel-pipe-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 14px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0;
        flex-wrap: wrap;
        transition: border-color 0.15s, background 0.15s;
        cursor: default;
    }
    .rel-pipe-card:hover {
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.13);
    }

    .rel-pipe-node {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 0.75rem;
        border-radius: 8px;
        border: 1px solid;
        font-size: 0.82rem;
        font-weight: 600;
        color: #fff;
        white-space: nowrap;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        position: relative;
    }
    .rel-pipe-node.workflow { background: rgba(79,156,249,0.12); border-color: rgba(79,156,249,0.4); }
    .rel-pipe-node.form     { background: rgba(74,222,128,0.12); border-color: rgba(74,222,128,0.4); }
    .rel-pipe-node.pdf      { background: rgba(244,114,182,0.12); border-color: rgba(244,114,182,0.4); }
    .rel-pipe-node.orphan   { background: rgba(248,113,113,0.06); border-color: rgba(248,113,113,0.35); border-style: dashed; }
    .rel-pipe-node .pn-icon { font-size: 0.95rem; flex-shrink: 0; }
    .rel-pipe-node .pn-name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .rel-pipe-node .pn-info-btn {
        margin-left: 4px;
        flex-shrink: 0;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.2);
        background: rgba(255,255,255,0.07);
        color: rgba(255,255,255,0.55);
        font-size: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.12s;
        font-style: italic;
        font-weight: 700;
    }
    .rel-pipe-node .pn-info-btn:hover { background: rgba(79,156,249,0.3); color: #fff; border-color: #4f9cf9; }

    .rel-pipe-arrow {
        color: rgba(255,255,255,0.25);
        font-size: 1rem;
        padding: 0 0.4rem;
        flex-shrink: 0;
    }

    .rel-pipe-missing {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.7rem;
        border-radius: 8px;
        border: 1px dashed rgba(248,113,113,0.3);
        font-size: 0.72rem;
        color: rgba(248,113,113,0.5);
        font-style: italic;
    }

    /* ─────────────────── MODAL ─────────────────── */
    .rel-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .rel-modal-overlay.open { display: flex; }

    .rel-modal {
        background: #1a1c23;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        max-width: 560px;
        width: 100%;
        max-height: 85vh;
        overflow-y: auto;
        animation: modalIn 0.2s ease-out;
    }
    @keyframes modalIn {
        from { transform: scale(0.95) translateY(10px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }

    .rel-modal-header {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1.25rem 1.5rem 0.75rem;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .rel-modal-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0;
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);
    }
    .rel-modal-icon.workflow { border-color: #4f9cf9; background: rgba(79,156,249,0.1); }
    .rel-modal-icon.form     { border-color: #4ade80; background: rgba(74,222,128,0.1); }
    .rel-modal-icon.pdf      { border-color: #f472b6; background: rgba(244,114,182,0.1); }

    .rel-modal-title-wrap { flex: 1; min-width: 0; }
    .rel-modal-title { font-size: 1rem; font-weight: 700; color: #fff; }
    .rel-modal-subtitle { font-size: 0.75rem; color: rgba(255,255,255,0.4); margin-top: 0.15rem; font-family: monospace; }
    .rel-modal-close {
        background: none; border: none; color: rgba(255,255,255,0.3);
        font-size: 1.2rem; cursor: pointer; padding: 0.2rem 0.4rem; transition: color 0.15s;
    }
    .rel-modal-close:hover { color: #f87171; }

    .rel-modal-body { padding: 1.25rem 1.5rem; }
    .rel-modal-section-label {
        font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.07em; color: rgba(255,255,255,0.35); margin: 1rem 0 0.5rem;
    }
    .rel-modal-section-label:first-child { margin-top: 0; }

    .rel-modal-linked-item {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.6rem 0.8rem;
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);
        border-radius: 8px; margin-bottom: 0.4rem; transition: border-color 0.15s; cursor: pointer;
    }
    .rel-modal-linked-item:hover { border-color: rgba(79,156,249,0.3); background: rgba(255,255,255,0.06); }
    .rel-modal-linked-item .li-icon { font-size: 1rem; width: 28px; text-align: center; }
    .rel-modal-linked-item .li-name { font-size: 0.82rem; color: #fff; font-weight: 500; }
    .rel-modal-linked-item .li-sub { font-size: 0.7rem; color: rgba(255,255,255,0.35); font-family: monospace; }
    .rel-modal-linked-item .li-badge {
        margin-left: auto; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.05em;
        padding: 0.1rem 0.4rem; border-radius: 4px; background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.4);
    }
    .rel-modal-linked-item .li-badge.workflow { color: #4f9cf9; background: rgba(79,156,249,0.1); }
    .rel-modal-linked-item .li-badge.form     { color: #4ade80; background: rgba(74,222,128,0.1); }
    .rel-modal-linked-item .li-badge.pdf      { color: #f472b6; background: rgba(244,114,182,0.1); }

    .rel-modal-stats {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        gap: 0.5rem; margin-bottom: 1rem;
    }
    .rel-modal-stat {
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);
        border-radius: 8px; padding: 0.5rem; text-align: center;
    }
    .rel-modal-stat .ms-value { font-size: 1.2rem; font-weight: 700; color: #fff; line-height: 1.2; }
    .rel-modal-stat .ms-label { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(255,255,255,0.35); margin-top: 0.2rem; }

    .rel-modal-footer {
        padding: 0.75rem 1.5rem 1.25rem;
        border-top: 1px solid rgba(255,255,255,0.06);
        display: flex; gap: 0.5rem; justify-content: flex-end;
    }
    .rel-modal-btn {
        padding: 0.4rem 1rem; border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05);
        color: rgba(255,255,255,0.6); font-size: 0.78rem; cursor: pointer;
        transition: all 0.15s; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.3rem;
    }
    .rel-modal-btn:hover { border-color: rgba(255,255,255,0.2); color: #fff; }
    .rel-modal-btn.primary { background: rgba(79,156,249,0.15); border-color: #4f9cf9; color: #4f9cf9; }
    .rel-modal-btn.primary:hover { background: rgba(79,156,249,0.25); }

    /* ── Light mode overrides ── */
    body.light .rel-header-title { color: #111827; }
    body.light .rel-header-sub { color: rgba(0,0,0,0.5); }
    body.light .rel-legend { color: rgba(0,0,0,0.6); }
    body.light .rel-stat-card { background: #fff; border-color: rgba(0,0,0,0.1); box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
    body.light .rel-stat-card.warn { border-color: rgba(248,113,113,0.5); background: #fff5f5; }
    body.light .rel-stat-num { color: #111827; }
    body.light .rel-stat-lbl { color: rgba(0,0,0,0.45); }
    body.light .rel-search { background: #fff; border-color: rgba(0,0,0,0.12); color: #111; }
    body.light .rel-toggle-btn { background: #fff; border-color: rgba(0,0,0,0.12); color: rgba(0,0,0,0.65); }
    body.light .rel-toggle-btn.active { background: rgba(79,156,249,0.12); border-color: #4f9cf9; color: #1d4ed8; }
    body.light .rel-graph-wrap { background: #fafafa; border-color: rgba(0,0,0,0.08); }
    body.light .rel-empty { color: rgba(0,0,0,0.4); }
    body.light .rel-table-wrap { background: #fafafa; border-color: rgba(0,0,0,0.08); }
    body.light .rel-table thead th { color: rgba(0,0,0,0.4); border-color: rgba(0,0,0,0.08); }
    body.light .rel-table tbody td { color: rgba(0,0,0,0.7); border-color: rgba(0,0,0,0.05); }
    body.light .rel-table tbody tr:hover { background: rgba(0,0,0,0.03); }
    body.light .rel-modal { background: #fff; border-color: rgba(0,0,0,0.1); }
    body.light .rel-modal-title { color: #111; }
    body.light .rel-modal-subtitle { color: rgba(0,0,0,0.4); }
    body.light .rel-modal-stat { background: #f5f5f7; border-color: rgba(0,0,0,0.06); }
    body.light .rel-modal-stat .ms-value { color: #111; }
    body.light .rel-modal-linked-item { background: #f5f5f7; border-color: rgba(0,0,0,0.06); }
    body.light .rel-modal-linked-item .li-name { color: #111; }
    body.light .rel-modal-footer { border-color: rgba(0,0,0,0.06); }
    body.light .rel-view-btn.active { background: rgba(79,156,249,0.12); color: #1d4ed8; }
    body.light .rel-view-toggle { border-color: rgba(0,0,0,0.08); background: #fff; }
    body.light .rel-view-btn { color: rgba(0,0,0,0.4); }
    body.light .rel-view-btn:hover { color: rgba(0,0,0,0.7); }
    body.light .rel-pipe-card { background: #fff; border-color: rgba(0,0,0,0.08); }
    body.light .rel-pipe-card:hover { background: #f5f5f7; }
    body.light .rel-pipe-arrow { color: rgba(0,0,0,0.25); }
    body.light .rel-focus-banner { background: rgba(79,156,249,0.07); border-color: rgba(79,156,249,0.2); }
    body.light .rel-focus-name { color: #111; }
    body.light .rel-focus-clear { background: rgba(0,0,0,0.05); color: rgba(0,0,0,0.5); }

    /* Pipeline nodes – light mode */
    body.light .rel-pipe-node { color: #111827; }
    body.light .rel-pipe-node.workflow { background: rgba(79,156,249,0.1); border-color: rgba(79,156,249,0.45); }
    body.light .rel-pipe-node.form     { background: rgba(74,222,128,0.1); border-color: rgba(74,222,128,0.45); }
    body.light .rel-pipe-node.pdf      { background: rgba(244,114,182,0.1); border-color: rgba(244,114,182,0.45); }
    body.light .rel-pipe-node.orphan   { background: rgba(248,113,113,0.06); border-color: rgba(248,113,113,0.4); color: #991b1b; }
    body.light .rel-pipe-node .pn-name { color: inherit; }
    body.light .rel-pipe-node .pn-info-btn { border-color: rgba(0,0,0,0.2); background: rgba(0,0,0,0.05); color: rgba(0,0,0,0.5); }
    body.light .rel-pipe-missing { border-color: rgba(248,113,113,0.3); color: rgba(185,28,28,0.6); }

    /* Table view – light mode */
    body.light .rel-table-section-title { color: rgba(0,0,0,0.45); border-color: rgba(0,0,0,0.08); }
    body.light .rel-link-chip { background: rgba(0,0,0,0.04); border-color: rgba(0,0,0,0.08); color: rgba(0,0,0,0.6); }
    body.light .type-badge.workflow { background: rgba(79,156,249,0.12); color: #1d4ed8; }
    body.light .type-badge.form     { background: rgba(74,222,128,0.12); color: #15803d; }
    body.light .type-badge.pdf      { background: rgba(244,114,182,0.12); color: #be185d; }
    body.light .type-badge.orphan   { background: rgba(248,113,113,0.12); color: #b91c1c; }

    /* Modal – light mode additional */
    body.light .rel-modal-close { color: rgba(0,0,0,0.35); }
    body.light .rel-modal-close:hover { color: #b91c1c; }
    body.light .rel-modal-section-label { color: rgba(0,0,0,0.4); }
    body.light .rel-modal-linked-item .li-sub { color: rgba(0,0,0,0.4); }
    body.light .rel-modal-linked-item .li-badge { background: rgba(0,0,0,0.05); color: rgba(0,0,0,0.5); }
    body.light .rel-modal-linked-item .li-badge.workflow { color: #1d4ed8; background: rgba(79,156,249,0.1); }
    body.light .rel-modal-linked-item .li-badge.form     { color: #15803d; background: rgba(74,222,128,0.1); }
    body.light .rel-modal-linked-item .li-badge.pdf      { color: #be185d; background: rgba(244,114,182,0.1); }
    body.light .rel-modal-btn { border-color: rgba(0,0,0,0.12); background: rgba(0,0,0,0.04); color: rgba(0,0,0,0.6); }
    body.light .rel-modal-btn:hover { border-color: rgba(0,0,0,0.2); color: #111; }
    body.light .rel-modal-stat .ms-label { color: rgba(0,0,0,0.4); }
    body.light .rel-modal-header { border-color: rgba(0,0,0,0.08); }
    body.light .rel-modal-body { color: #111; }

    /* Graph SVG – light mode text colours */
    body.light .rel-graph-wrap svg text { fill: #111827; }
    body.light .rel-graph-wrap svg .rel-node rect { fill: rgba(0,0,0,0.03); }
    body.light .rel-graph-wrap svg .rel-node text:nth-child(3) { fill: rgba(0,0,0,0.55); } /* subtitle */
    body.light .rel-graph-wrap svg path[stroke] { stroke: rgba(0,0,0,0.6); }
    body.light .rel-graph-wrap svg .rel-node-info-btn rect { fill: rgba(0,0,0,0.05); stroke: rgba(0,0,0,0.15); }
    body.light .rel-graph-wrap svg .rel-node-info-btn text { fill: rgba(0,0,0,0.45); }
</style>
@endpush

@section('content')
<div class="rel-page">

    <div class="rel-header">
        <div>
            <div class="rel-header-title">🔗 Liens & Dépendances</div>
            <div class="rel-header-sub">Vue d'ensemble des connexions entre Workflows, Formulaires et Templates PDF</div>
        </div>
        <div class="rel-legend">
            <span class="rel-legend-item"><span class="rel-legend-dot" style="background:#4f9cf9;"></span> Workflow</span>
            <span class="rel-legend-item"><span class="rel-legend-dot" style="background:#4ade80;"></span> Formulaire</span>
            <span class="rel-legend-item"><span class="rel-legend-dot" style="background:#f472b6;"></span> Template PDF</span>
            <span class="rel-legend-item"><span class="rel-legend-dot" style="background:#f87171;"></span> Non lié (orphelin)</span>
        </div>
    </div>

    <div class="rel-stats-bar" id="relStatsBar">
        <div class="rel-stat-card"><div class="rel-stat-num" id="relStatWf">—</div><div class="rel-stat-lbl">Workflows</div></div>
        <div class="rel-stat-card"><div class="rel-stat-num" id="relStatForm">—</div><div class="rel-stat-lbl">Formulaires</div></div>
        <div class="rel-stat-card"><div class="rel-stat-num" id="relStatPdf">—</div><div class="rel-stat-lbl">Templates PDF</div></div>
        <div class="rel-stat-card warn"><div class="rel-stat-num" id="relStatOrphan">—</div><div class="rel-stat-lbl">⚠ Éléments non liés</div></div>
    </div>

    <div class="rel-toolbar">
        <input class="rel-search" id="relSearch" placeholder="🔍 Rechercher un nom…" oninput="relFilter()">
        <button class="rel-toggle-btn active" id="relToggleAll" onclick="relSetFilter('all', this)">Tout afficher</button>
        <button class="rel-toggle-btn" id="relToggleLiés" onclick="relSetFilter('linked', this)">🔗 Liés uniquement</button>
        <button class="rel-toggle-btn" id="relToggleOrphans" onclick="relSetFilter('orphans', this)">⚠ Orphelins uniquement</button>
        <button class="rel-toggle-btn" onclick="relReload()">↻ Rafraîchir</button>

        <!-- View toggle — three modes -->
        <div class="rel-view-toggle">
            <button class="rel-view-btn" id="btnViewGraph" onclick="relSwitchView('graph')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.59 13.51 6.83 3.98M15.41 6.51l-6.82 3.98"/></svg>
                Graphe
            </button>
            <button class="rel-view-btn active" id="btnViewPipeline" onclick="relSwitchView('pipeline')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                Pipeline
            </button>
            <button class="rel-view-btn" id="btnViewTable" onclick="relSwitchView('table')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/></svg>
                Tableau
            </button>
        </div>
    </div>

    <!-- Focus mode banner -->
    <div class="rel-focus-banner" id="relFocusBanner">
        <span>Focus sur :</span>
        <span class="rel-focus-name" id="relFocusName"></span>
        <span style="color:rgba(255,255,255,0.4);font-size:0.75rem;" id="relFocusDesc"></span>
        <button class="rel-focus-clear" onclick="relClearFocus()">✕ Tout afficher</button>
    </div>

    <!-- Graph View -->
    <div class="rel-graph-wrap" id="relGraphWrap" style="display:none;">
    </div>

    <!-- Pipeline View -->
    <div class="rel-pipeline-wrap visible" id="relPipelineWrap">
        <div class="rel-pipeline-list" id="relPipelineList"><div style="padding:2rem;text-align:center;color:rgba(255,255,255,0.35);">Chargement…</div></div>
    </div>

    <!-- Table View -->
    <div class="rel-table-wrap" id="relTableWrap">
        <div id="relTableContent"></div>
    </div>

</div>

<!-- Detail Modal -->
<div class="rel-modal-overlay" id="relModal">
    <div class="rel-modal" onclick="event.stopPropagation()">
        <div class="rel-modal-header">
            <div class="rel-modal-icon" id="modalIcon">📄</div>
            <div class="rel-modal-title-wrap">
                <div class="rel-modal-title" id="modalTitle">—</div>
                <div class="rel-modal-subtitle" id="modalSubtitle">—</div>
            </div>
            <button class="rel-modal-close" onclick="closeModal()">✕</button>
        </div>
        <div class="rel-modal-body" id="modalBody"></div>
        <div class="rel-modal-footer">
            <button class="rel-modal-btn" onclick="closeModal()">Fermer</button>
            <a class="rel-modal-btn primary" id="modalEditBtn" href="#" target="_blank">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Ouvrir
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const REL_DATA_URL = '/admin/relationships/data';

const REL_COLORS = {
    workflow: '#4f9cf9',
    form:     '#4ade80',
    pdf:      '#f472b6',
    orphan:   '#f87171',
};

const REL_ICONS = {
    workflow: '⏳',
    form:     '📋',
    pdf:      '📄',
};

let _relData     = { nodes: [], edges: [] };
let _relFilterMode = 'all';
let _relSearchTerm = '';
let _relViewMode   = 'pipeline';
let _focusNodeId   = null;   // ← NEW: currently focused node

// ─────────────────── LOAD ───────────────────
async function relLoad() {
    // Show a loading indicator in the currently-active view
    const pipeList = document.getElementById('relPipelineList');
    if (_relViewMode === 'pipeline' && pipeList) {
        pipeList.innerHTML = '<div style="padding:2rem;text-align:center;color:rgba(255,255,255,0.35);font-size:0.9rem;">Chargement…</div>';
    }

    try {
        const res = await fetch(REL_DATA_URL, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const json = await res.json();
        if (json.error) throw new Error(json.error);

        _relData = json;
        document.getElementById('relStatWf').textContent     = json.stats.workflows_total;
        document.getElementById('relStatForm').textContent   = json.stats.forms_total;
        document.getElementById('relStatPdf').textContent    = json.stats.pdf_total;
        document.getElementById('relStatOrphan').textContent = json.stats.orphans_total;

        relRender();
    } catch (e) {
        console.error('Relationships load error:', e);
        const errHtml = `<div class="rel-empty"><div style="font-size:2rem;">⚠️</div><div>Impossible de charger les données. ${e.message}</div></div>`;
        if (pipeList) pipeList.innerHTML = errHtml;
        document.getElementById('relGraphWrap').innerHTML = errHtml;
    }
}

function relReload() { _focusNodeId = null; relLoad(); }

// ─────────────────── FILTER / SEARCH ───────────────────
function relSetFilter(mode, btnEl) {
    _relFilterMode = mode;
    _focusNodeId   = null;
    hideFocusBanner();
    document.querySelectorAll('.rel-toggle-btn').forEach(b => b.classList.remove('active'));
    btnEl.classList.add('active');
    relRender();
}

function relFilter() {
    _relSearchTerm = document.getElementById('relSearch').value.trim().toLowerCase();
    _focusNodeId   = null;
    hideFocusBanner();
    relRender();
}

// ─────────────────── VIEW SWITCHER ───────────────────
function relSwitchView(view) {
    _relViewMode = view;

    document.getElementById('btnViewGraph').classList.toggle('active',    view === 'graph');
    document.getElementById('btnViewPipeline').classList.toggle('active', view === 'pipeline');
    document.getElementById('btnViewTable').classList.toggle('active',    view === 'table');

    document.getElementById('relGraphWrap').style.display    = view === 'graph'    ? 'block' : 'none';
    document.getElementById('relPipelineWrap').style.display = view === 'pipeline' ? 'block' : 'none';
    document.getElementById('relTableWrap').style.display    = view === 'table'    ? 'block' : 'none';

    relRender();
}

// ─────────────────── FILTERED NODES ───────────────────
function getFilteredNodes() {
    let nodes = _relData.nodes || [];
    if (_relFilterMode === 'orphans') {
        nodes = nodes.filter(n => n.orphan);
    } else if (_relFilterMode === 'linked') {
        // Keep only nodes that have at least one edge connecting them to another node
        const edges = _relData.edges || [];
        const connectedIds = new Set();
        edges.forEach(e => { connectedIds.add(e.from); connectedIds.add(e.to); });
        nodes = nodes.filter(n => connectedIds.has(n.id));
    }
    if (_relSearchTerm) nodes = nodes.filter(n =>
        (n.label || '').toLowerCase().includes(_relSearchTerm) ||
        (n.subtitle || '').toLowerCase().includes(_relSearchTerm)
    );
    return nodes;
}

// Returns IDs of nodes connected to focusId (plus focusId itself)
function getFocusGroup(focusId) {
    const edges = _relData.edges || [];
    const group = new Set([focusId]);
    edges.forEach(e => {
        if (e.from === focusId) group.add(e.to);
        if (e.to   === focusId) group.add(e.from);
    });
    return group;
}

// ─────────────────── FOCUS BANNER ───────────────────
function showFocusBanner(node) {
    const banner  = document.getElementById('relFocusBanner');
    const nameEl  = document.getElementById('relFocusName');
    const descEl  = document.getElementById('relFocusDesc');
    const group   = getFocusGroup(node.id);
    nameEl.textContent = node.label;
    descEl.textContent = `— ${group.size - 1} élément(s) lié(s)`;
    banner.classList.add('visible');
}
function hideFocusBanner() {
    document.getElementById('relFocusBanner').classList.remove('visible');
}

function relClearFocus() {
    _focusNodeId = null;
    hideFocusBanner();
    relRender();
}

// ─────────────────── MAIN RENDER ───────────────────
function relRender() {
    if (_relViewMode === 'graph')    relRenderGraph();
    else if (_relViewMode === 'pipeline') relRenderPipeline();
    else                             relRenderTable();
}

// ─────────────────── GRAPH VIEW ───────────────────
function relRenderGraph() {
    const wrap = document.getElementById('relGraphWrap');
    wrap.style.display = 'block';
    document.getElementById('relPipelineWrap').style.display = 'none';
    document.getElementById('relTableWrap').style.display    = 'none';

    const nodes = getFilteredNodes();

    if (nodes.length === 0) {
        wrap.innerHTML = '<div class="rel-empty"><div style="font-size:2rem;">📭</div><div>Aucun élément à afficher pour ce filtre.</div></div>';
        return;
    }

    const nodeIds      = new Set(nodes.map(n => n.id));
    const visibleEdges = (_relData.edges || []).filter(e => nodeIds.has(e.from) && nodeIds.has(e.to));

    // — focus group
    const focusGroup = _focusNodeId ? getFocusGroup(_focusNodeId) : null;

    const COL_X  = { workflow: 40, form: 380, pdf: 720 };
    const ROW_H  = 86;
    const NODE_W = 260;
    const NODE_H = 64;
    const TOP_PAD = 40;

    const byType = { workflow: [], form: [], pdf: [] };
    nodes.forEach(n => byType[n.type]?.push(n));

    const positions = {};
    ['workflow', 'form', 'pdf'].forEach(type => {
        byType[type].forEach((n, i) => {
            positions[n.id] = { x: COL_X[type], y: TOP_PAD + i * ROW_H };
        });
    });

    const maxRows  = Math.max(byType.workflow.length, byType.form.length, byType.pdf.length, 1);
    const svgH     = TOP_PAD * 2 + maxRows * ROW_H;
    // Make SVG wide enough for all three columns + right padding
    const svgW     = COL_X.pdf + NODE_W + 60;

    let svg = `<svg width="${svgW}" height="${svgH}" viewBox="0 0 ${svgW} ${svgH}" style="font-family:inherit;overflow:visible;">`;
    svg += `<defs>
        <marker id="arrow" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
            <path d="M0,0 L0,6 L8,3 z" fill="rgba(255,255,255,0.25)" />
        </marker>
    </defs>`;

    // Column headers
    svg += colHeader(COL_X.workflow, 'WORKFLOWS',     REL_COLORS.workflow);
    svg += colHeader(COL_X.form,     'FORMULAIRES',   REL_COLORS.form);
    svg += colHeader(COL_X.pdf,      'TEMPLATES PDF', REL_COLORS.pdf);

    // Edges — dimmed when focus active and not in group
    visibleEdges.forEach(e => {
        const a = positions[e.from], b = positions[e.to];
        if (!a || !b) return;
        const x1 = a.x + NODE_W, y1 = a.y + NODE_H / 2;
        const x2 = b.x,          y2 = b.y + NODE_H / 2;
        const midX = (x1 + x2) / 2;

        const inFocus = !focusGroup || (focusGroup.has(e.from) && focusGroup.has(e.to));
        const strokeOpacity = inFocus ? '0.35' : '0.04';
        const strokeColor   = inFocus && focusGroup ? '#4f9cf9' : 'rgba(255,255,255,1)';
        const strokeWidth   = inFocus && focusGroup ? '2' : '1.6';

        svg += `<path d="M ${x1} ${y1} C ${midX} ${y1}, ${midX} ${y2}, ${x2} ${y2}"
                 stroke="${strokeColor}" stroke-opacity="${strokeOpacity}"
                 stroke-width="${strokeWidth}" fill="none" marker-end="url(#arrow)" />`;
    });

    // Nodes
    nodes.forEach(n => {
        const pos   = positions[n.id];
        if (!pos) return;
        const color  = n.orphan ? REL_COLORS.orphan : REL_COLORS[n.type];
        const dashed = n.orphan ? 'stroke-dasharray="5,4"' : '';
        const icon   = REL_ICONS[n.type] || '•';

        const inFocus   = !focusGroup || focusGroup.has(n.id);
        const nodeOpacity = inFocus ? '1' : '0.1';
        const glowFilter  = (focusGroup && n.id === _focusNodeId) ? `filter="url(#glow)"` : '';

        svg += `
        <g class="rel-node" data-node-id="${escAttr(n.id)}"
           style="cursor:pointer;opacity:${nodeOpacity};transition:opacity 0.25s;"
           transform="translate(${pos.x},${pos.y})">
            <rect width="${NODE_W}" height="${NODE_H}" rx="10"
                  fill="rgba(255,255,255,0.04)" stroke="${color}" stroke-width="1.6" ${dashed} />
            <text x="14" y="24" font-size="13" font-weight="700" fill="#fff">${icon} ${truncate(escText(n.label), 24)}</text>
            <text x="14" y="42" font-size="10.5" fill="rgba(255,255,255,0.45)">${truncate(escText(n.subtitle || ''), 32)}</text>
            ${n.orphan ? `<text x="${NODE_W - 22}" y="20" font-size="13">⚠️</text>` : ''}
            <!-- Small info button — top-right corner -->
            <g class="rel-node-info-btn" data-node-id="${escAttr(n.id)}" transform="translate(${NODE_W - 20}, 4)">
                <rect width="16" height="16" rx="8" fill="rgba(255,255,255,0.07)" stroke="rgba(255,255,255,0.15)" stroke-width="1"/>
                <text x="8" y="12" text-anchor="middle" font-size="10" font-style="italic" font-weight="700" fill="rgba(255,255,255,0.5)">i</text>
            </g>
        </g>`;
    });

    svg += '</svg>';
    wrap.innerHTML = svg;

    // Attach click: node body → focus, info button → modal
    wrap.querySelectorAll('.rel-node').forEach(el => {
        const nodeId = el.getAttribute('data-node-id');
        const node   = _relData.nodes.find(n => n.id === nodeId);

        el.addEventListener('click', (e) => {
            e.stopPropagation();

            // If clicking the info button (inner <g>)
            if (e.target.closest('.rel-node-info-btn')) {
                if (node) openDetailModal(node);
                return;
            }

            // Otherwise: toggle focus
            if (_focusNodeId === nodeId) {
                _focusNodeId = null;
                hideFocusBanner();
            } else {
                _focusNodeId = nodeId;
                if (node) showFocusBanner(node);
            }
            relRenderGraph();
        });
    });
}

function colHeader(x, label, color) {
    return `<text x="${x}" y="20" font-size="10.5" font-weight="700" letter-spacing="1.2"
             fill="${color}">${label}</text>`;
}

// ─────────────────── PIPELINE VIEW ───────────────────
// Shows one card per "complete chain": Workflow → Form → PDF
// Orphans get their own row at the bottom.
function relRenderPipeline() {
    const wrap = document.getElementById('relPipelineWrap');
    wrap.style.display = 'block';
    document.getElementById('relGraphWrap').style.display    = 'none';
    document.getElementById('relTableWrap').style.display    = 'none';

    const filteredNodes = getFilteredNodes();
    const filteredIds   = new Set(filteredNodes.map(n => n.id));
    const edges         = _relData.edges || [];

    const list = document.getElementById('relPipelineList');
    list.innerHTML = '';

    // Build chains starting from workflows → forms → pdfs
    const usedForms = new Set();
    const usedPdfs  = new Set();
    const rows      = [];

    // 1) Chains with a workflow
    const workflows = filteredNodes.filter(n => n.type === 'workflow');
    workflows.forEach(wf => {
        const linkedForms = edges
            .filter(e => e.from === wf.id && filteredIds.has(e.to))
            .map(e => _relData.nodes.find(n => n.id === e.to))
            .filter(Boolean);

        if (linkedForms.length === 0) {
            rows.push({ workflow: wf, form: null, pdf: null });
        } else {
            linkedForms.forEach(form => {
                usedForms.add(form.id);
                const linkedPdf = edges
                    .filter(e => e.from === form.id && filteredIds.has(e.to))
                    .map(e => _relData.nodes.find(n => n.id === e.to))
                    .filter(Boolean)[0] || null;
                if (linkedPdf) usedPdfs.add(linkedPdf.id);
                rows.push({ workflow: wf, form, pdf: linkedPdf });
            });
        }
    });

    // 2) Forms without a workflow
    const unlinkedForms = filteredNodes.filter(n => n.type === 'form' && !usedForms.has(n.id));
    unlinkedForms.forEach(form => {
        const linkedPdf = edges
            .filter(e => e.from === form.id && filteredIds.has(e.to))
            .map(e => _relData.nodes.find(n => n.id === e.to))
            .filter(Boolean)[0] || null;
        if (linkedPdf) usedPdfs.add(linkedPdf.id);
        rows.push({ workflow: null, form, pdf: linkedPdf });
    });

    // 3) PDFs without a form
    const unlinkedPdfs = filteredNodes.filter(n => n.type === 'pdf' && !usedPdfs.has(n.id));
    unlinkedPdfs.forEach(pdf => {
        rows.push({ workflow: null, form: null, pdf });
    });

    if (rows.length === 0) {
        list.innerHTML = '<div class="rel-empty"><div style="font-size:2rem;">📭</div><div>Aucun élément à afficher pour ce filtre.</div></div>';
        return;
    }

    rows.forEach(row => {
        const card = document.createElement('div');
        card.className = 'rel-pipe-card';

        let html = '';

        if (row.workflow) {
            html += pipeNode(row.workflow);
        } else {
            html += `<div class="rel-pipe-missing">— Pas de Workflow —</div>`;
        }

        html += `<div class="rel-pipe-arrow">→</div>`;

        if (row.form) {
            html += pipeNode(row.form);
        } else {
            html += `<div class="rel-pipe-missing">— Pas de Formulaire —</div>`;
        }

        html += `<div class="rel-pipe-arrow">→</div>`;

        if (row.pdf) {
            html += pipeNode(row.pdf);
        } else {
            html += `<div class="rel-pipe-missing">— Pas de PDF —</div>`;
        }

        card.innerHTML = html;

        // Info button clicks → open modal
        card.querySelectorAll('.pn-info-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const nodeId = btn.getAttribute('data-node-id');
                const node   = _relData.nodes.find(n => n.id === nodeId);
                if (node) openDetailModal(node);
            });
        });

        list.appendChild(card);
    });
}

function pipeNode(node) {
    const cls  = node.orphan ? 'orphan' : node.type;
    const icon = REL_ICONS[node.type] || '•';
    return `<div class="rel-pipe-node ${cls}" title="${escAttr(node.label)}">
        <span class="pn-icon">${icon}</span>
        <span class="pn-name">${escText(truncate(node.label, 22))}</span>
        <span class="pn-info-btn" data-node-id="${escAttr(node.id)}" title="Voir les détails">i</span>
    </div>`;
}

// ─────────────────── TABLE VIEW ───────────────────
function relRenderTable() {
    const wrap = document.getElementById('relTableWrap');
    wrap.style.display = 'block';
    document.getElementById('relGraphWrap').style.display    = 'none';
    document.getElementById('relPipelineWrap').style.display = 'none';

    const nodes   = getFilteredNodes();
    const content = document.getElementById('relTableContent');

    if (nodes.length === 0) {
        content.innerHTML = '<div class="rel-empty"><div style="font-size:2rem;">📭</div><div>Aucun élément à afficher pour ce filtre.</div></div>';
        return;
    }

    const byType = { workflow: [], form: [], pdf: [] };
    nodes.forEach(n => byType[n.type]?.push(n));

    let html = '';

    if (byType.workflow.length) {
        html += `<div class="rel-table-section">
            <div class="rel-table-section-title" style="color:#4f9cf9;">⏳ Workflows</div>
            <table class="rel-table">
                <thead><tr>
                    <th>Nom</th><th>Process Key</th><th>Statut</th><th>Formulaires liés</th><th></th>
                </tr></thead><tbody>`;
        byType.workflow.forEach(n => {
            html += `<tr onclick="openDetailModal(getNodeById('${n.id}'))">
                <td><strong>${escText(n.label)}</strong></td>
                <td style="font-size:0.75rem;color:rgba(255,255,255,0.4);">${escText(n.subtitle || '—')}</td>
                <td><span class="type-badge ${n.active ? 'form' : 'orphan'}">${n.active ? 'Actif' : 'Inactif'}</span></td>
                <td>${n.meta?.linked_forms_count ?? 0}</td>
                <td>${n.orphan ? '<span class="type-badge orphan">⚠ Orphelin</span>' : ''}</td>
            </tr>`;
        });
        html += `</tbody></table></div>`;
    }

    if (byType.form.length) {
        html += `<div class="rel-table-section">
            <div class="rel-table-section-title" style="color:#4ade80;">📋 Formulaires</div>
            <table class="rel-table">
                <thead><tr>
                    <th>Titre</th><th>Statut</th><th>Workflow lié</th><th>PDF lié</th><th>Soumissions</th><th></th>
                </tr></thead><tbody>`;
        byType.form.forEach(n => {
            const linked   = getLinkedNodes(n.id);
            const workflow = linked.find(l => l.type === 'workflow');
            const pdf      = linked.find(l => l.type === 'pdf');
            html += `<tr onclick="openDetailModal(getNodeById('${n.id}'))">
                <td><strong>${escText(n.label)}</strong></td>
                <td style="font-size:0.75rem;color:rgba(255,255,255,0.4);">${escText(n.subtitle || '—')}</td>
                <td>${workflow ? `<span class="rel-link-chip">⏳ ${escText(workflow.label)}</span>` : '—'}</td>
                <td>${pdf      ? `<span class="rel-link-chip">📄 ${escText(pdf.label)}</span>`      : '—'}</td>
                <td>${n.meta?.submissions_count ?? 0}</td>
                <td>${n.orphan ? '<span class="type-badge orphan">⚠ Orphelin</span>' : ''}</td>
            </tr>`;
        });
        html += `</tbody></table></div>`;
    }

    if (byType.pdf.length) {
        html += `<div class="rel-table-section">
            <div class="rel-table-section-title" style="color:#f472b6;">📄 Templates PDF</div>
            <table class="rel-table">
                <thead><tr>
                    <th>Nom</th><th>Template Key</th><th>Type</th><th>Formulaire lié</th><th></th>
                </tr></thead><tbody>`;
        byType.pdf.forEach(n => {
            const linked = getLinkedNodes(n.id);
            const form   = linked.find(l => l.type === 'form');
            html += `<tr onclick="openDetailModal(getNodeById('${n.id}'))">
                <td><strong>${escText(n.label)}</strong></td>
                <td style="font-size:0.75rem;color:rgba(255,255,255,0.4);font-family:monospace;">${escText(n.subtitle || '—')}</td>
                <td style="font-size:0.75rem;color:rgba(255,255,255,0.4);">${escText(n.meta?.template_type || '—')}</td>
                <td>${form ? `<span class="rel-link-chip">📋 ${escText(form.label)}</span>` : '—'}</td>
                <td>${n.orphan ? '<span class="type-badge orphan">⚠ Orphelin</span>' : ''}</td>
            </tr>`;
        });
        html += `</tbody></table></div>`;
    }

    content.innerHTML = html;
}

// ─────────────────── LINKED NODES HELPER ───────────────────
function getLinkedNodes(nodeId) {
    const edges = _relData.edges || [];
    return edges
        .filter(e => e.from === nodeId || e.to === nodeId)
        .map(e => {
            const otherId = e.from === nodeId ? e.to : e.from;
            return _relData.nodes.find(n => n.id === otherId);
        })
        .filter(Boolean);
}

// ─────────────────── DETAIL MODAL ───────────────────
function openDetailModal(node) {
    if (!node) return;

    const modal    = document.getElementById('relModal');
    const icon     = document.getElementById('modalIcon');
    const title    = document.getElementById('modalTitle');
    const subtitle = document.getElementById('modalSubtitle');
    const body     = document.getElementById('modalBody');
    const editBtn  = document.getElementById('modalEditBtn');

    icon.textContent = REL_ICONS[node.type] || '📄';
    icon.className   = `rel-modal-icon ${node.type}`;
    title.textContent    = node.label;
    subtitle.textContent = node.subtitle || node.type;
    editBtn.href = node.edit_url || '#';

    let html = '';

    html += `<div class="rel-modal-stats">`;
    if (node.type === 'workflow') {
        html += modalStat(node.meta?.linked_forms_count ?? 0, 'Formulaires liés');
        html += modalStat(node.active ? '✓' : '✗', 'Statut');
    } else if (node.type === 'form') {
        html += modalStat(node.meta?.submissions_count ?? 0, 'Soumissions');
        html += modalStat(node.meta?.demandes_count ?? 0, 'Demandes');
        html += modalStat(node.meta?.has_pdf ? '✓' : '✗', 'PDF lié');
    } else if (node.type === 'pdf') {
        html += modalStat(node.meta?.template_type || '—', 'Type');
        html += modalStat(node.meta?.is_active ? 'Actif' : 'Inactif', 'Statut');
    }
    html += `</div>`;

    if (node.orphan) {
        html += `<div style="display:flex;align-items:center;gap:0.5rem;padding:0.6rem 0.8rem;background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.2);border-radius:8px;margin-bottom:1rem;color:#f87171;font-size:0.82rem;">
            <span style="font-size:1.2rem;">⚠️</span>
            <span>Cet élément n'est lié à aucun autre</span>
        </div>`;
    }

    const connected = getLinkedNodes(node.id);
    if (connected.length > 0) {
        const edges    = _relData.edges || [];
        const incoming = connected.filter(c => edges.some(e => e.to === node.id && e.from === c.id));
        const outgoing = connected.filter(c => edges.some(e => e.from === node.id && e.to === c.id));

        if (incoming.length > 0) {
            html += `<div class="rel-modal-section-label">↙ Reçoit de</div>`;
            incoming.forEach(c => { html += modalLinkedItem(c); });
        }
        if (outgoing.length > 0) {
            html += `<div class="rel-modal-section-label">↗ Envoie vers</div>`;
            outgoing.forEach(c => { html += modalLinkedItem(c); });
        }
    } else if (!node.orphan) {
        html += `<div style="color:rgba(255,255,255,0.3);text-align:center;padding:1rem;">Aucune connexion détectée</div>`;
    }

    body.innerHTML = html;
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    document.body.style.pointerEvents = '';
    document.documentElement.style.pointerEvents = '';
}

function modalStat(value, label) {
    return `<div class="rel-modal-stat">
        <div class="ms-value">${value}</div>
        <div class="ms-label">${label}</div>
    </div>`;
}

function modalLinkedItem(node) {
    const icon      = REL_ICONS[node.type] || '•';
    const badgeClass = node.type || 'orphan';
    return `<div class="rel-modal-linked-item" onclick="openDetailModal(getNodeById('${node.id}')); event.stopPropagation();">
        <span class="li-icon">${icon}</span>
        <div>
            <div class="li-name">${escText(node.label)}</div>
            <div class="li-sub">${escText(node.subtitle || '')}</div>
        </div>
        <span class="li-badge ${badgeClass}">${node.type || '?'}</span>
        ${node.orphan ? '<span style="font-size:0.7rem;color:#f87171;">⚠</span>' : ''}
    </div>`;
}

function closeModal(e) {
    // Called from button (no arg), ESC (no arg), or overlay click (e present)
    // When called from overlay click, only close if the click was directly on the overlay
    if (e && e.target !== document.getElementById('relModal')) return;
    const modal = document.getElementById('relModal');
    modal.classList.remove('open');
    document.body.style.overflow = '';
    // Re-enable pointer events on the rest of the page explicitly
    document.documentElement.style.pointerEvents = '';
    document.body.style.pointerEvents = '';
}

// ─────────────────── HELPERS ───────────────────
function getNodeById(id) { return _relData.nodes.find(n => n.id === id); }

function escText(s)  { return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function escAttr(s)  { return String(s ?? '').replace(/"/g,'&quot;'); }
function truncate(s, n) { return s.length > n ? s.slice(0, n - 1) + '…' : s; }

// ─────────────────── KEYBOARD ───────────────────
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (document.getElementById('relModal').classList.contains('open')) {
            closeModal();
        } else if (_focusNodeId) {
            relClearFocus();
        }
    }
});

// ─────────────────── INIT ───────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Overlay click-outside to close modal (only fires when clicking the dark overlay itself)
    document.getElementById('relModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('relModal')) {
            closeModal();
        }
    });
    relLoad();
});
</script>
@endpush
