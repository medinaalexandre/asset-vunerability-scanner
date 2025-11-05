<?php

namespace App\Services;

use App\Models\Vulnerability;
use App\Models\VulnerabilityFact;
use Carbon\Carbon;

class ClickHouseWriterService
{
    public function upsertVulnerabilityFacts(Vulnerability $vulnerability): void
    {
        $vulnerability->loadMissing('assets');
        $facts = [];

        foreach ($vulnerability->assets as $asset) {
            $facts[] = [
                'asset_id' => $asset->id,
                'cve_id' => $vulnerability->cve_id,
                'asset_criticality' => $asset->criticality_level->value,
                'asset_weight' => $asset->criticality_level->getWeight(),
                'cvss_base_score' => $vulnerability->cvss_base_score,
                'calculated_severity' => $vulnerability->severity->value,
                'published_at' => $vulnerability->published_at,
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
        }

        VulnerabilityFact::prepareAndInsertBulk($facts);
    }
}
