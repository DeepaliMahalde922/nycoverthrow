<?php

$checks = MGLInstagramGallery_Admin_PluginStatus::getPluginStatus();

echo MGLInstagramGallery_Admin_PluginStatusRenderer::renderChecks( $checks );
