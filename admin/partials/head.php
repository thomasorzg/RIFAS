<?php
require ("session.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
  <meta content="Semantic-UI-Forest, collection of design, themes and templates for Semantic-UI." name="description">
  <meta content="Semantic-UI, Theme, Design, Template" name="keywords">
  <meta content="PPType" name="author">
  <meta content="#ffffff" name="theme-color">
  <title>Administración</title>
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="static/dist/semantic-ui/semantic.min.css">
  <link rel="stylesheet" type="text/css" href="static/stylesheets/default.css">
  <link rel="stylesheet" type="text/css" href="static/stylesheets/datepicker.css">
  <link rel="stylesheet" type="text/css" href="static/stylesheets/pandoc-code-highlight.css">
  <!-- JavaScript -->
  <script type="text/javascript" src="static/dist/jquery/jquery.min.js"></script>
  <script type="text/javascript" src="static/dist/semantic-ui/semantic.min.js"></script>
  <script type="text/javascript" src="static/datepicker.js"></script>
  <script type="text/javascript" src="admin.js"></script>
  <!-- Style CSS -->
  <style type="text/css">
    body {
      display: relative;
    }

    #sidebar {
      position: fixed;
      top: 51.8px;
      left: 0;
      bottom: 0;
      width: 18%;
      background-color: #f5f5f5;
      padding: 0px;
    }

    #sidebar .ui.menu {
      margin: 2em 0 0;
      font-size: 16px;
    }

    #sidebar .ui.menu > a.item {
      color: #337ab7;
      border-radius: 0 !important;
    }

    #sidebar .ui.menu > a.item.active {
      background-color: #337ab7;
      color: white;
      border: none !important;
    }

    #sidebar .ui.menu > a.item:hover {
      background-color: #4f93ce;
      color: white;
    }
      
    #content {
      margin-left: 19%;
      width: 81%;
      margin-top: 3em;
      padding-left: 3em;
      float: left;
    }

    #content > .ui.grid {
      padding-right: 4em;
      padding-bottom: 3em;
    }

    #content h1 {
      font-size: 36px;
    }

    #content .ui.divider:not(.hidden) {
      margin: 0;
    }

    #content table.ui.table {
      border: none;
    }

    #content table.ui.table thead th {
      border-bottom: 2px solid #eee !important;
    }
  </style>
</head>