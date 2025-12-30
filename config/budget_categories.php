<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Budget Categories
    |--------------------------------------------------------------------------
    |
    | These are the standard budget categories with recommended percentage
    | allocations based on the 50/30/20 rule and best financial practices.
    |
    | Categories are grouped into: Needs (50%), Wants (30%), Savings (20%)
    |
    */

    'categories' => [
        // NEEDS - 50% of after-tax income
        'needs' => [
            'housing' => [
                'name' => 'Housing/Rent',
                'description' => 'Rent, mortgage, property taxes, HOA fees',
                'default_percentage' => 25.0, // 25% of total income (50% of needs)
                'group' => 'needs',
            ],
            'utilities' => [
                'name' => 'Utilities',
                'description' => 'Electric, water, gas, trash, internet, phone',
                'default_percentage' => 5.0,
                'group' => 'needs',
            ],
            'groceries' => [
                'name' => 'Groceries',
                'description' => 'Food and household supplies',
                'default_percentage' => 10.0,
                'group' => 'needs',
            ],
            'transportation' => [
                'name' => 'Transportation',
                'description' => 'Gas, car payment, insurance, maintenance, public transit',
                'default_percentage' => 8.0,
                'group' => 'needs',
            ],
            'insurance' => [
                'name' => 'Insurance',
                'description' => 'Health, life, disability insurance',
                'default_percentage' => 2.0,
                'group' => 'needs',
            ],
        ],

        // WANTS - 30% of after-tax income
        'wants' => [
            'dining_out' => [
                'name' => 'Dining Out/Takeout',
                'description' => 'Restaurants, fast food, delivery',
                'default_percentage' => 8.0,
                'group' => 'wants',
            ],
            'entertainment' => [
                'name' => 'Entertainment',
                'description' => 'Movies, concerts, events, hobbies',
                'default_percentage' => 5.0,
                'group' => 'wants',
            ],
            'subscriptions' => [
                'name' => 'Subscriptions',
                'description' => 'Netflix, Spotify, gym, etc.',
                'default_percentage' => 3.0,
                'group' => 'wants',
            ],
            'shopping' => [
                'name' => 'Shopping',
                'description' => 'Clothing, electronics, non-essentials',
                'default_percentage' => 7.0,
                'group' => 'wants',
            ],
            'personal_care' => [
                'name' => 'Personal Care',
                'description' => 'Haircuts, beauty, spa, self-care',
                'default_percentage' => 3.0,
                'group' => 'wants',
            ],
            'travel' => [
                'name' => 'Travel',
                'description' => 'Vacations, trips, getaways',
                'default_percentage' => 4.0,
                'group' => 'wants',
            ],
        ],

        // SAVINGS & DEBT - 20% of after-tax income
        'savings' => [
            'emergency_fund' => [
                'name' => 'Emergency Fund',
                'description' => 'Emergency savings (3-6 months expenses)',
                'default_percentage' => 10.0,
                'group' => 'savings',
            ],
            'investments' => [
                'name' => 'Investments',
                'description' => 'Retirement, stocks, 401k, IRA',
                'default_percentage' => 7.0,
                'group' => 'savings',
            ],
            'debt_payment' => [
                'name' => 'Debt Payment',
                'description' => 'Credit card, student loans, extra payments',
                'default_percentage' => 3.0,
                'group' => 'savings',
            ],
        ],

        // MISCELLANEOUS - Flexible
        'other' => [
            'medical' => [
                'name' => 'Medical/Healthcare',
                'description' => 'Doctor visits, prescriptions, co-pays',
                'default_percentage' => 0.0, // Variable
                'group' => 'needs',
            ],
            'childcare' => [
                'name' => 'Childcare/Education',
                'description' => 'Daycare, tuition, school supplies',
                'default_percentage' => 0.0, // Variable
                'group' => 'needs',
            ],
            'pet_care' => [
                'name' => 'Pet Care',
                'description' => 'Food, vet, grooming',
                'default_percentage' => 0.0, // Variable
                'group' => 'wants',
            ],
            'gifts' => [
                'name' => 'Gifts/Donations',
                'description' => 'Birthdays, holidays, charity',
                'default_percentage' => 0.0, // Variable
                'group' => 'wants',
            ],
            'car_repair' => [
                'name' => 'Car Repair/Maintenance',
                'description' => 'Unexpected repairs, maintenance',
                'default_percentage' => 0.0, // Variable, part of transportation
                'group' => 'needs',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Budget Allocation Rules
    |--------------------------------------------------------------------------
    |
    | The 50/30/20 rule and custom percentage handling
    |
    */

    'allocation_rules' => [
        'needs' => 50,   // 50% for needs
        'wants' => 30,   // 30% for wants
        'savings' => 20, // 20% for savings/debt
    ],

    /*
    |--------------------------------------------------------------------------
    | Category Groups
    |--------------------------------------------------------------------------
    |
    | Organize categories into logical groups
    |
    */

    'groups' => [
        'needs' => [
            'label' => 'Needs (50%)',
            'description' => 'Essential expenses you must pay',
            'color' => 'red',
        ],
        'wants' => [
            'label' => 'Wants (30%)',
            'description' => 'Non-essential but desired expenses',
            'color' => 'blue',
        ],
        'savings' => [
            'label' => 'Savings & Debt (20%)',
            'description' => 'Future planning and debt reduction',
            'color' => 'green',
        ],
    ],
];
