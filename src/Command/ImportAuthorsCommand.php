<?php



namespace App\Command;

use App\Entity\Author;
use App\Entity\AuthorLinks;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-authors',
    description: 'Import authors and their social networks from CSV file',
)]
class ImportAuthorsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->addArgument('csv-file', InputArgument::OPTIONAL, 'Path to the CSV file', 'var/data/authors.csv');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $csvFile = $input->getArgument('csv-file');

        if (!file_exists($csvFile)) {
            $io->error("The file '$csvFile' does not exist");
            return Command::FAILURE;
        }

        $io->info("Importing from file: $csvFile");

        if (($handle = fopen($csvFile, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');
            $count = 0;

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $lastName = trim($data[0]);
                $firstName = trim($data[1]);
                $author = new Author();
                $author->setFirstName($firstName);
                $author->setLastName($lastName);
                $author->setBiography('');
                $author->setPhotUrl('');
                $author->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($author);

                $io->text("Auteur: $firstName $lastName");

                // Loop through all URL columns
                for ($i = 2; $i < count($data); $i++) {
                    $url = trim($data[$i]);
                    if ($url !== '') {
                        $type = stripos($url, 'instagram') !== false ? 'instagram' : 'website';
                        $link = new AuthorLinks();
                        $link->setAuthor($author);
                        $link->setUrl($url);
                        $link->setType($type);
                        $this->entityManager->persist($link);

                        $io->text("  → $url ($type)");
                    }
                }
                $count++;
                $io->newLine();
            }
            fclose($handle);
            $this->entityManager->flush();
        }

        $io->success("Import finished! $count authors imported.");
        return Command::SUCCESS;
    }
}
