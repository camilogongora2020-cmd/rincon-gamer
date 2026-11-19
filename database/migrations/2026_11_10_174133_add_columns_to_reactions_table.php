
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Las columnas y restricciones ya fueron creadas
        // en la migración create_reactions_table.
        // No es necesario volver a agregarlas.
    }

    public function down(): void
    {
        // No eliminamos columnas porque pertenecen
        // a la migración original de reactions.
    }
};
