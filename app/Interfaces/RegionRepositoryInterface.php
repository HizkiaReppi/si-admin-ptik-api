<?php

namespace App\Interfaces;

interface RegionRepositoryInterface
{
    /**
     * Mengambil daftar semua provinsi.
     *
     * @return array
     */
    public function getProvinces(): array;

    /**
     * Mengambil daftar kabupaten/kota berdasarkan kode provinsi.
     *
     * @param string $provinceCode
     * @return array
     */
    public function getRegencies(string $provinceCode): array;

    /**
     * Mengambil daftar kecamatan berdasarkan kode kabupaten/kota.
     *
     * @param string $regencyCode
     * @return array
     */
    public function getDistricts(string $regencyCode): array;

    /**
     * Mengambil daftar kelurahan/desa berdasarkan kode kecamatan.
     *
     * @param string $districtCode
     * @return array
     */
    public function getVillages(string $districtCode): array;
}
