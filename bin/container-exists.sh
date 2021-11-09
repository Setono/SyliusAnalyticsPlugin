#!/bin/bash
[[ -f tests/Application/var/cache/test/ApplicationTests_Setono_SyliusAnalyticsPlugin_Application_KernelTestDebugContainer.xml ]] || tests/Application/bin/console cache:warmup --env=test
