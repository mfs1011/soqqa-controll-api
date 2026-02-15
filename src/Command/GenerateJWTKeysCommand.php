<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:jwt:generate',
    description: 'Add a short description for your command',
    aliases: ['app:jwt:generate', 'a:j:g'],
)]
class GenerateJWTKeysCommand extends Command
{
    public function __construct(
        private readonly ParameterBagInterface $params
    ) {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {

        $projectDir = $this->params->get('kernel.project_dir');
        $jwtDir = $projectDir . '/config/jwt';

        if (!is_dir($jwtDir)) {
            mkdir($jwtDir, 0777, true);
            $output->writeln('<info>JWT directory created.</info>');
        }

        $privateKeyPath = $jwtDir . '/private.pem';
        $publicKeyPath  = $jwtDir . '/public.pem';

        $passphrase = $_ENV['JWT_PASSPHRASE']
            ?? $_SERVER['JWT_PASSPHRASE']
            ?? null;

        if (!$passphrase) {
            $output->writeln(
                '<error>JWT_PASSPHRASE not found in .env</error>'
            );
            return Command::FAILURE;
        }

        // Private key generate
        $output->writeln('Generating private key...');

        $privateKey = openssl_pkey_new([
            'private_key_bits' => 4096,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        openssl_pkey_export(
            $privateKey,
            $privateKeyString,
            $passphrase
        );

        file_put_contents(
            $privateKeyPath,
            $privateKeyString
        );

        // Public key
        $details = openssl_pkey_get_details($privateKey);

        file_put_contents(
            $publicKeyPath,
            $details['key']
        );

        chmod($privateKeyPath, 0600);
        chmod($publicKeyPath, 0644);

        $output->writeln(
            '<info>JWT keys generated successfully.</info>'
        );

        return Command::SUCCESS;
    }
}
