<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AutoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $autores = array(
            // Literatura universal clásica
            'Miguel de Cervantes',
            'William Shakespeare',
            'Gabriel García Márquez',
            'Jorge Luis Borges',
            'Fyodor Dostoyevsky',
            'León Tolstói',
            'Victor Hugo',
            'Charles Dickens',
            'Jane Austen',
            'Mark Twain',
            'Edgar Allan Poe',
            'Franz Kafka',
            'Hermann Hesse',
            'Albert Camus',
            'Jean-Paul Sartre',
            'Simone de Beauvoir',
            'Marcel Proust',
            'James Joyce',
            'Federico García Lorca',
            'Antonio Machado',
            'Gustavo Adolfo Bécquer',
            'Lope de Vega',
            'Francisco de Quevedo',
            'Rubén Darío',
            'Pablo Neruda',
            'Octavio Paz',
            'Julio Cortázar',
            'Mario Vargas Llosa',
            'Carlos Fuentes',
            'Juan Rulfo',
            'Miguel Ángel Asturias',
            'Isabel Allende',
            'Jorge Ibargüengoitia',
            'Elena Poniatowska',
            'Rosario Castellanos',
            'Manuel Puig',
            'Julio Ramón Ribeyro',
            'César Vallejo',
            'José Martí',
            'José Hernández',
            'Domingo Faustino Sarmiento',
            'Esteban Echeverría',
            'Ricardo Güiraldes',
            'Roberto Arlt',
            'Adolfo Bioy Casares',
            'Ernesto Sábato',
            'Leopoldo Lugones',
            'Horacio Quiroga',
            'Delmira Agustini',
            'Juana de Ibarbourou',
            'Mario Benedetti',
            'Juan Carlos Onetti',
            'Felisberto Hernández',
            'José Enrique Rodó',
            'Gabriela Mistral',
            'Vicente Huidobro',
            'Nicanor Parra',
            'Joaquín Edwards Bello',
            'Mariano Latorre',
            'Volodia Teitelboim',

            // Autores venezolanos
            'Rómulo Gallegos',
            'Teresa de la Parra',
            'Arturo Uslar Pietri',
            'Miguel Otero Silva',
            'Adriano González León',
            'Salvador Garmendia',
            'José Rafael Pocaterra',
            'Rufino Blanco Fombona',
            'Manuel Vicente Magallanes',
            'Andrés Eloy Blanco',
            'Aquiles Nazoa',
            'Alfredo Sadel',
            'Armando Reverón',
            'Antonio Lauro',
            'Ramón Díaz Sánchez',
            'Miguel Ramón Utrera',
            'Guillermo Meneses',
            'Antonia Palacios',
            'Luisa Josefina Hernández',
            'Julio Garmendia',
            'Pedro Sotillo',
            'Enrique Bernardo Núñez',
            'Alfredo Armas Alfonzo',
            'Francisco Herrera Luque',
            'Francisco Massiani',
            'Edmundo Aray',
            'Alfredo Chacón',
            'Elizabeth Schön',
            'Hannibal Vaux',
            'Fernando Cabrices',
            'Juan Sánchez Peláez',
            'Rafael Cadenas',
            'Eugenio Montejo',
            'Yolanda Osweta',
            'Blanca Strepponi',
            'Ana Enriqueta Terán',
            'Ida Gramcko',
            'Anteo Ortega',
            'Sergio Medina',
            'Delfina Torrealba',

            // Literatura infantil, juvenil y fantasía
            'Hans Christian Andersen',
            'Los Hermanos Grimm',
            'Lewis Carroll',
            'Robert Louis Stevenson',
            'Jules Verne',
            'H. G. Wells',
            'Rudyard Kipling',
            'Astrid Lindgren',
            'Roald Dahl',
            'Antoine de Saint-Exupéry',
            'J. R. R. Tolkien',
            'C. S. Lewis',
            'Laura Ingalls Wilder',
            'Louisa May Alcott',
            'Johanna Spyri',
            'Carlo Collodi',
            'Daniel Defoe',
            'Jonathan Swift',
            'Mary Shelley',
            'Bram Stoker',
            'Arthur Conan Doyle',
            'Agatha Christie',
            'Jules Renard',
            'Kenneth Grahame',
            'Frances Hodgson Burnett',
            'L. Frank Baum',
            'Eleanor H. Porter',
            'Louise Erdrich',
            'Elena Fortún',
            'Gloria Fuertes',

            // Pensamiento, historia y divulgación
            'Platón',
            'Aristóteles',
            'Sócrates',
            'Sun Tzu',
            'Niccolò Maquiavelo',
            'Thomas More',
            'René Descartes',
            'Immanuel Kant',
            'Friedrich Nietzsche',
            'Karl Marx',
            'Friedrich Engels',
            'Simón Rodríguez',
            'Simón Bolívar',
            'Andrés Bello',
            'Miguel de Unamuno',
            'Ortega y Gasset',
            'Bertrand Russell',
            'Sigmund Freud',
            'Carl Jung',
            'Stephen Hawking',
            'Carl Sagan',
            'Charles Darwin',
            'Isaac Newton',
            'Marie Curie',
            'Albert Einstein',
            'Galileo Galilei',
            'Johannes Kepler',
            'Nicolás Copérnico',
            'Antoine Lavoisier',
            'Alexander von Humboldt',
        );

        // Evitar duplicados si el seeder se ejecuta más de una vez
        // (el campo nombre es UNIQUE)
        $existentes = DB::table('autores')->pluck('nombre')->all();

        $datos = array();
        foreach ($autores as $autor) {
            if (in_array($autor, $existentes)) {
                continue;
            }
            $datos[] = array(
                'nombre'     => $autor,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            );
        }

        if (!empty($datos)) {
            DB::table('autores')->insert($datos);
        }
    }
}
