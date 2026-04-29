<?php

use App\Models\MouvementLivreModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class MouvementLivreModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $seed = App\Database\Seeds\BibliothequeSeeder::class;

    public function testEtatCourantSelonMouvements(): void
    {
        $model = new MouvementLivreModel();

        $this->assertSame('DISPONIBLE', $model->getEtatCourantLivre(1));

        $model->insert([
            'id_livre' => 1,
            'type_mouvement' => 'EMPRUNT',
            'nom_emprunteur' => 'Alice',
            'date_mouvement' => date('Y-m-d H:i:s'),
        ]);

        $this->assertSame('EMPRUNTE', $model->getEtatCourantLivre(1));

        $model->insert([
            'id_livre' => 1,
            'type_mouvement' => 'RETOUR',
            'nom_emprunteur' => null,
            'date_mouvement' => date('Y-m-d H:i:s'),
        ]);

        $this->assertSame('DISPONIBLE', $model->getEtatCourantLivre(1));
    }
}
