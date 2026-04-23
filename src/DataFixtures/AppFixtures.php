<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Carpooling;
use App\Entity\DriverPreferences;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Entity\Wallet;
use App\Entity\WalletTransaction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = $this->createUser(
            manager: $manager,
            email: 'admin@ecoride.fr',
            firstName: 'Camille',
            lastName: 'Martin',
            pseudo: 'camille_admin',
            roles: ['ROLE_ADMIN', 'ROLE_USER'],
            address: '12 avenue de la Republique, Paris',
            phone: '0601020304',
            birthDate: new \DateTimeImmutable('1990-05-14'),
            walletBalance: 150
        );

        $lea = $this->createDriverWithVehicle(
            manager: $manager,
            email: 'lea@ecoride.fr',
            firstName: 'Lea',
            lastName: 'Dubois',
            pseudo: 'lea_roule',
            address: '18 rue des Lilas, Lille',
            phone: '0611223344',
            birthDate: new \DateTimeImmutable('1993-02-11'),
            walletBalance: 96,
            vehicleData: [
                'plateNumber' => 'AA-123-ER',
                'brand' => 'Peugeot',
                'model' => '208',
                'color' => 'Blanc',
                'seats' => 4,
                'isElectric' => false,
                'firstRegistrationAt' => new \DateTimeImmutable('2021-04-10'),
            ],
            preferencesData: [
                'smokingAllowed' => false,
                'petsAllowed' => true,
                'extras' => ['Musique douce', 'Pause cafe'],
            ],
            transactionAmount: 18
        );

        $nora = $this->createDriverWithVehicle(
            manager: $manager,
            email: 'nora@ecoride.fr',
            firstName: 'Nora',
            lastName: 'Bernard',
            pseudo: 'nora_electrique',
            address: '5 allee des Pins, Lyon',
            phone: '0622334455',
            birthDate: new \DateTimeImmutable('1989-09-03'),
            walletBalance: 124,
            vehicleData: [
                'plateNumber' => 'BB-456-ER',
                'brand' => 'Tesla',
                'model' => 'Model 3',
                'color' => 'Noir',
                'seats' => 4,
                'isElectric' => true,
                'firstRegistrationAt' => new \DateTimeImmutable('2022-07-18'),
            ],
            preferencesData: [
                'smokingAllowed' => false,
                'petsAllowed' => false,
                'extras' => ['Chargeur telephone', 'Climatisation'],
            ],
            transactionAmount: 26
        );

        $yassine = $this->createDriverWithVehicle(
            manager: $manager,
            email: 'yassine@ecoride.fr',
            firstName: 'Yassine',
            lastName: 'Petit',
            pseudo: 'yass_sur_la_route',
            address: '44 boulevard Voltaire, Marseille',
            phone: '0633445566',
            birthDate: new \DateTimeImmutable('1995-12-09'),
            walletBalance: 88,
            vehicleData: [
                'plateNumber' => 'CC-789-ER',
                'brand' => 'Renault',
                'model' => 'Clio',
                'color' => 'Bleu',
                'seats' => 4,
                'isElectric' => false,
                'firstRegistrationAt' => new \DateTimeImmutable('2020-01-20'),
            ],
            preferencesData: [
                'smokingAllowed' => false,
                'petsAllowed' => true,
                'extras' => ['Discussion sympa', 'Bagages acceptes'],
            ],
            transactionAmount: 12
        );

        $manon = $this->createPassenger(
            manager: $manager,
            email: 'manon@ecoride.fr',
            firstName: 'Manon',
            lastName: 'Roux',
            pseudo: 'manon_voyage',
            address: '23 rue Nationale, Tours',
            phone: '0644556677',
            birthDate: new \DateTimeImmutable('1998-06-21'),
            walletBalance: 72
        );

        $julien = $this->createPassenger(
            manager: $manager,
            email: 'julien@ecoride.fr',
            firstName: 'Julien',
            lastName: 'Moreau',
            pseudo: 'julien_passager',
            address: '9 quai de la Fosse, Nantes',
            phone: '0655667788',
            birthDate: new \DateTimeImmutable('1991-11-07'),
            walletBalance: 54
        );

        $tripLilleParis = $this->createTrip(
            manager: $manager,
            driver: $lea['user'],
            vehicle: $lea['vehicle'],
            originCity: 'Lille',
            destinationCity: 'Paris',
            departureAt: new \DateTimeImmutable('+1 day 08:00'),
            arrivalAt: new \DateTimeImmutable('+1 day 10:45'),
            seatsTotal: 4,
            seatsAvailable: 2,
            priceCredits: 17,
            ecoTag: false,
            status: 'planifie'
        );

        $tripLilleAmiens = $this->createTrip(
            manager: $manager,
            driver: $lea['user'],
            vehicle: $lea['vehicle'],
            originCity: 'Lille',
            destinationCity: 'Amiens',
            departureAt: new \DateTimeImmutable('+2 days 07:30'),
            arrivalAt: new \DateTimeImmutable('+2 days 09:00'),
            seatsTotal: 4,
            seatsAvailable: 3,
            priceCredits: 10,
            ecoTag: false,
            status: 'planifie'
        );

        $tripLyonParis = $this->createTrip(
            manager: $manager,
            driver: $nora['user'],
            vehicle: $nora['vehicle'],
            originCity: 'Lyon',
            destinationCity: 'Paris',
            departureAt: new \DateTimeImmutable('+1 day 09:15'),
            arrivalAt: new \DateTimeImmutable('+1 day 13:45'),
            seatsTotal: 4,
            seatsAvailable: 1,
            priceCredits: 28,
            ecoTag: true,
            status: 'planifie'
        );

        $tripLyonGrenoble = $this->createTrip(
            manager: $manager,
            driver: $nora['user'],
            vehicle: $nora['vehicle'],
            originCity: 'Lyon',
            destinationCity: 'Grenoble',
            departureAt: new \DateTimeImmutable('+3 days 18:00'),
            arrivalAt: new \DateTimeImmutable('+3 days 19:40'),
            seatsTotal: 4,
            seatsAvailable: 2,
            priceCredits: 12,
            ecoTag: true,
            status: 'planifie'
        );

        $tripMarseilleNice = $this->createTrip(
            manager: $manager,
            driver: $yassine['user'],
            vehicle: $yassine['vehicle'],
            originCity: 'Marseille',
            destinationCity: 'Nice',
            departureAt: new \DateTimeImmutable('+2 days 06:45'),
            arrivalAt: new \DateTimeImmutable('+2 days 09:30'),
            seatsTotal: 4,
            seatsAvailable: 3,
            priceCredits: 19,
            ecoTag: false,
            status: 'planifie'
        );

        $tripMarseilleMontpellier = $this->createTrip(
            manager: $manager,
            driver: $yassine['user'],
            vehicle: $yassine['vehicle'],
            originCity: 'Marseille',
            destinationCity: 'Montpellier',
            departureAt: new \DateTimeImmutable('+4 days 14:00'),
            arrivalAt: new \DateTimeImmutable('+4 days 16:10'),
            seatsTotal: 4,
            seatsAvailable: 2,
            priceCredits: 16,
            ecoTag: false,
            status: 'planifie'
        );

        $this->createBooking($manager, $tripLilleParis, $manon, 'confirme');
        $this->createBooking($manager, $tripLilleParis, $julien, 'confirme');
        $this->createBooking($manager, $tripLyonParis, $manon, 'en_attente');

        $manager->flush();
    }

    private function createDriverWithVehicle(
        ObjectManager $manager,
        string $email,
        string $firstName,
        string $lastName,
        string $pseudo,
        string $address,
        string $phone,
        \DateTimeImmutable $birthDate,
        int $walletBalance,
        array $vehicleData,
        array $preferencesData,
        int $transactionAmount,
    ): array {
        $user = $this->createUser(
            manager: $manager,
            email: $email,
            firstName: $firstName,
            lastName: $lastName,
            pseudo: $pseudo,
            roles: ['ROLE_USER', 'ROLE_DRIVER', 'ROLE_PASSENGER'],
            address: $address,
            phone: $phone,
            birthDate: $birthDate,
            walletBalance: $walletBalance
        );

        $preferences = (new DriverPreferences())
            ->setUser($user)
            ->setSmokingAllowed($preferencesData['smokingAllowed'])
            ->setPetsAllowed($preferencesData['petsAllowed'])
            ->setExtras($preferencesData['extras']);

        $vehicle = (new Vehicle())
            ->setOwner($user)
            ->setPlateNumber($vehicleData['plateNumber'])
            ->setBrand($vehicleData['brand'])
            ->setModel($vehicleData['model'])
            ->setColor($vehicleData['color'])
            ->setSeats($vehicleData['seats'])
            ->setIsElectric($vehicleData['isElectric'])
            ->setActive(true)
            ->setFirstRegistrationAt($vehicleData['firstRegistrationAt']);

        $transaction = (new WalletTransaction())
            ->setWallet($user->getWallet())
            ->setType('reservation_en_attente')
            ->setAmount($transactionAmount);

        $manager->persist($preferences);
        $manager->persist($vehicle);
        $manager->persist($transaction);

        return [
            'user' => $user,
            'vehicle' => $vehicle,
        ];
    }

    private function createPassenger(
        ObjectManager $manager,
        string $email,
        string $firstName,
        string $lastName,
        string $pseudo,
        string $address,
        string $phone,
        \DateTimeImmutable $birthDate,
        int $walletBalance,
    ): User {
        return $this->createUser(
            manager: $manager,
            email: $email,
            firstName: $firstName,
            lastName: $lastName,
            pseudo: $pseudo,
            roles: ['ROLE_USER', 'ROLE_PASSENGER'],
            address: $address,
            phone: $phone,
            birthDate: $birthDate,
            walletBalance: $walletBalance
        );
    }

    private function createUser(
        ObjectManager $manager,
        string $email,
        string $firstName,
        string $lastName,
        string $pseudo,
        array $roles,
        string $address,
        string $phone,
        \DateTimeImmutable $birthDate,
        int $walletBalance,
    ): User {
        $user = (new User())
            ->setEmail($email)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPseudo($pseudo)
            ->setRoles($roles)
            ->setAddress($address)
            ->setPhone($phone)
            ->setDateOfBirthAt($birthDate)
            ->setIsVerified(true)
            ->setFirstConnexion(false);

        $user->setPassword($this->passwordHasher->hashPassword($user, 'ecoride123'));

        $wallet = (new Wallet())
            ->setOwner($user)
            ->setBalance($walletBalance);

        $user->setWallet($wallet);

        $manager->persist($user);
        $manager->persist($wallet);

        return $user;
    }

    private function createTrip(
        ObjectManager $manager,
        User $driver,
        Vehicle $vehicle,
        string $originCity,
        string $destinationCity,
        \DateTimeImmutable $departureAt,
        \DateTimeImmutable $arrivalAt,
        int $seatsTotal,
        int $seatsAvailable,
        int $priceCredits,
        bool $ecoTag,
        string $status,
    ): Carpooling {
        $trip = (new Carpooling())
            ->setDriver($driver)
            ->setVehicle($vehicle)
            ->setOriginCity($originCity)
            ->setDestinationCity($destinationCity)
            ->setDepartureAt($departureAt)
            ->setArrivalAt($arrivalAt)
            ->setSeatsTotal($seatsTotal)
            ->setSeatsAvailable($seatsAvailable)
            ->setPriceCredits($priceCredits)
            ->setEcoTag($ecoTag)
            ->setStatus($status)
            ->setDurationMinutes((int) (($arrivalAt->getTimestamp() - $departureAt->getTimestamp()) / 60));

        $manager->persist($trip);

        return $trip;
    }

    private function createBooking(ObjectManager $manager, Carpooling $trip, User $passenger, string $status): void
    {
        $booking = (new Booking())
            ->setTrip($trip)
            ->setPassager($passenger)
            ->setStatus($status);

        $manager->persist($booking);
    }
}
