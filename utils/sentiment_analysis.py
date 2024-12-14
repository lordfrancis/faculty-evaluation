from textblob import TextBlob
import nltk
from nltk.tokenize import sent_tokenize
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from nltk.probability import FreqDist
import sys
import json

try:
    # Download required NLTK data
    nltk.download('punkt', quiet=True)
    nltk.download('stopwords', quiet=True)
    nltk.download('averaged_perceptron_tagger', quiet=True)

    def analyze_sentiment(text):
        # Create TextBlob object
        analysis = TextBlob(text)
        
        # Adjust sentiment thresholds
        sentiment_score = analysis.sentiment.polarity
        
        # Modified thresholds for more balanced categorization
        if sentiment_score > 0.1:
            category = "Positive"
        elif sentiment_score < -0.1:
            category = "Negative"
        else:
            category = "Neutral"

        # Generate summary
        sentences = sent_tokenize(text)
        words = word_tokenize(text.lower())
        stop_words = set(stopwords.words('english'))
        
        # Remove stopwords and non-alphabetic words
        words = [word for word in words if word.isalpha() and word not in stop_words]
        
        # Get word frequency
        freq_dist = FreqDist(words)
        most_common = freq_dist.most_common(5)
        
        # Extract key phrases
        key_phrases = []
        for sentence in analysis.sentences:
            for np in sentence.noun_phrases:
                key_phrases.append(np)

        result = {
            "score": sentiment_score,
            "category": category,
            "frequent_terms": [word for word, _ in most_common],
            "key_phrases": key_phrases[:5] if key_phrases else [],
            "summary": " ".join(sentences[:2]) if sentences else "",
            "positive_comments": [sentence for sentence in sentences if TextBlob(sentence).sentiment.polarity > 0.1],
            "neutral_comments": [sentence for sentence in sentences if -0.1 <= TextBlob(sentence).sentiment.polarity <= 0.1],
            "negative_comments": [sentence for sentence in sentences if TextBlob(sentence).sentiment.polarity < -0.1]
        }
        
        # Debug output
        sys.stderr.write(f"Analysis result: {json.dumps(result)}\n")
        
        return result

    if __name__ == "__main__":
        if len(sys.argv) < 2:
            sys.stderr.write("No input text provided\n")
            sys.exit(1)
            
        input_text = sys.argv[1]
        try:
            result = analyze_sentiment(input_text)
            print(json.dumps(result))
        except Exception as e:
            sys.stderr.write(f"Error analyzing text: {str(e)}\n")
            sys.exit(1)

except Exception as e:
    sys.stderr.write(f"Initialization error: {str(e)}\n")
    sys.exit(1)
