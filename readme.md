# Faculty Evaluation System with NLP Analysis

A web-based faculty evaluation system that leverages Natural Language Processing (NLP) to analyze and categorize student feedback comments. The system provides automated sentiment analysis of student evaluations, helping to derive meaningful insights from textual feedback.

## Features

- **User Management**
  - Student Registration
  - Program Head Registration
  - Authentication System

- **Course Management**
  - Add/Manage Courses
  - Course Rating System

- **Evaluation Analysis**
  - Sentiment Analysis of Comments
  - Rating Summary and Statistics
  - Comment Categorization (Positive/Neutral/Negative)

## Technology Stack

- **Backend**
  - PHP 8.1.10
  - Python 3.13
  - MySQL Database

- **NLP Components**
  - TextBlob
  - NLTK (Natural Language Toolkit)

## Requirements

- Laragon (or similar local development environment)
- PHP >= 8.1.10
- Python >= 3.13
- MySQL
- Python packages:
  - textblob
  - nltk

## Installation

1. **Setup Development Environment**
   - Install Laragon
   - Ensure PHP 8.1.10 is configured
   - Setup MySQL database

2. **Python Dependencies**
   ```bash
   pip install textblob nltk
   ```

3. **Database Setup**
   - Create a new MySQL database
   - Import the provided SQL schema

4. **Configure Application**
   - Clone the repository to your Laragon www directory
   - Configure database connection settings
   - Ensure Python path is correctly set in your environment

## Usage

1. Start your Laragon environment
2. Access the application through your local development URL
3. Register as either a Student or Program Head
4. Begin using the evaluation features

## System Flow
1. Create an account for the Program Head. Login using that account. 
2. Create courses for evaluation.
3. Create student account and log in as a student.
4. Click on an available course and submit course evaluations
5. The system processes the textual feedback using NLP
6. Program Heads can view analyzed results and summaries
7. Sentiment analysis categorizes comments and provides insights

## Note

This system is designed for educational institutions to streamline their faculty evaluation process. The NLP component helps in automatically processing and categorizing student feedback, making it easier to derive actionable insights. Features are limited as this is only a test system. 