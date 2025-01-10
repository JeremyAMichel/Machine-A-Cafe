<?php

final class MachineACafe {
    private string $marque;
    private int $nombreDossettes;
    private bool $estEnFonction;

    public function __construct(string $marque) {
        $this->marque = $marque;
        $this->nombreDossettes = 0;
        $this->estEnFonction = false;
    }

    public function getMarque(): string {
        return $this->marque;
    }

    public function getNombreDossettes(): int {
        return $this->nombreDossettes;
    }

    public function getEstEnFonction(): bool {
        return $this->estEnFonction;
    }

    public function allumage(): void 
    {
        $this->estEnFonction = !$this->estEnFonction;
    }

    public function ajouterDossettes(): void 
    {
        if($this->nombreDossettes >= 1) {
            return;
        }
        $this->nombreDossettes += 1;
    }

    public function faireCafe(): void 
    {
        if($this->nombreDossettes < 1) {
            return;
        }
        $this->nombreDossettes -= 1;
    }
}