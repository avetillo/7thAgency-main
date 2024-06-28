
CREATE TABLE applicant_profile (
    applicantID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    fullName VARCHAR(60) NOT NULL,
    address VARCHAR(80) NOT NULL,
    city VARCHAR(30) NOT NULL,
    region VARCHAR(20) NOT NULL,
    zip INT NOT NULL,
    homePhone CHAR(20) NULL,
    cellPhone CHAR(20) NOT NULL,
    emailAddress VARCHAR(60) NOT NULL,
    sssNumber VARCHAR(15) NOT NULL,
    birthDate DATE NOT NULL,
    age INT NOT NULL,
    citizenship VARCHAR(30) NOT NULL,
    sex CHAR(1) NOT NULL,
    maritalStatus CHAR(2) NOT NULL
);

CREATE TABLE application_information (
    applicationID VARCHAR(10) PRIMARY KEY NOT NULL,
    desiredPosition VARCHAR(50) NOT NULL,
    availWDate DATE NOT NULL,
    desiredSalary VARCHAR(9) NOT NULL,
    desiredEmployment VARCHAR(2) NOT NULL,
    applicantID INT NOT NULL,
    FOREIGN KEY (applicantID) REFERENCES applicant_profile(applicantID)
);

CREATE TABLE educational_background (
    educalbackID VARCHAR(10) PRIMARY KEY NOT NULL,
    yearsAttended CHAR(10) NOT NULL,
    degree VARCHAR(70) NULL,
    major VARCHAR(40) NULL,
    applicantID INT NOT NULL,
    schoolID VARCHAR(10) NOT NULL,
    FOREIGN KEY (applicantID) REFERENCES applicant_profile(applicantID),
    FOREIGN KEY (schoolID) REFERENCES school_details(schoolID)
);

CREATE TABLE school_details (
  schoolID VARCHAR(10) NOT NULL,
  schoolName VARCHAR(100) NOT NULL,
  location VARCHAR(20) NOT NULL,
  PRIMARY KEY (schoolID));
  
CREATE TABLE work_experience (
  employerID VARCHAR(10) PRIMARY KEY NOT NULL,
  employer VARCHAR(60) NOT NULL,
  employerContact CHAR(20) NOT NULL,
  employerAddress VARCHAR(40) NOT NULL,
  dateEmployed DATE NOT NULL,
  employedPosition VARCHAR(40) NOT NULL,
  reasonforLeaving VARCHAR(40) NOT NULL,
  applicantID INT NOT NULL,
  FOREIGN KEY (applicantID) REFERENCES applicant_profile(applicantID)
);
  
CREATE TABLE character_reference (
    referenceID VARCHAR(10) PRIMARY KEY NOT NULL,
    refName VARCHAR(60) NOT NULL,
    refTitle VARCHAR(40) NOT NULL,
    refCompany VARCHAR(40) NOT NULL,
    refPhone CHAR(20) NOT NULL,
	applicantID INT NOT NULL,
	FOREIGN KEY (applicantID) REFERENCES applicant_profile(applicantID)
);