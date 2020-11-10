 create table users (
    userID      int PRIMARY KEY,
    username    varchar(32) UNIQUE,
    password    char(64), # SHA256 hash
    email       varchar(128) UNIQUE,
    firstName   varchar(32),
    lastName    varchar(32),
    dateJoined  DATE
);
    
    create table finances (
        userID          int,
        incomePercent   int,
        expensesPercent int,
        FOREIGN KEY (userID) REFERENCES users(userID)
    );
    
        create table income (
            userID      int,
            frequency   varchar(32),
            amount      decimal(8,2),
            category    varchar(32),
            details     varchar(128),
            FOREIGN KEY (userID) REFERENCES users(userID)
        );
        
        create table expenses (
            userID      int,
            frequency   varchar(32),
            amount      decimal(8,2),
            category    varchar(32),
            details     varchar(128),
            FOREIGN KEY (userID) REFERENCES users(userID)
        );
        
        create table savings (
            userID      int,
            savings     decimal(8,2),
            savingsGoal decimal(8,2),
            FOREIGN KEY (userID) REFERENCES users(userID)
        );
        
    create table needs (
        userID      int,
        need        varchar(32),
        budget      decimal(8,2),
        spent       decimal(8,2),
        FOREIGN KEY (userID) REFERENCES users(userID)
    );

    create table wants (
        userID      int,
        want        varchar(32),
        budget      decimal(8,2),
        spent       decimal(8,2),
        FOREIGN KEY (userID) REFERENCES users(userID)
    );
