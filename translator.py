from transformers import T5ForConditionalGeneration, T5Tokenizer
import torch
import sys
import re

# Load the trained model and tokenizer
model = T5ForConditionalGeneration.from_pretrained("./trained_model")
tokenizer = T5Tokenizer.from_pretrained("./trained_model")

# Define the device (GPU if available, otherwise CPU)
device = torch.device("cuda" if torch.cuda.is_available() else "cpu")
model.to(device)  # Move the model to the appropriate device

def translate_text(input_text):
    # Tokenize input and move to the same device as the model
    inputs = tokenizer(input_text, return_tensors="pt", padding=True, truncation=True).to(device)

    # Generate output and move back to CPU for decoding
    outputs = model.generate(input_ids=inputs["input_ids"], max_length=512)
    translated_text = tokenizer.decode(outputs[0], skip_special_tokens=True)

    return translated_text

# Example usage
# input_sentence = "Mo lo si oja l'ana"
input_sentence = " ".join(sys.argv[1:])
# print(input_sentence)
input_sentence = input_sentence.lower()
translated_sentence = translate_text(input_sentence)


import re

# Function to insert 'n' before 'g' if the last word doesn't end with 'n'
def modify_last_word(segment):
    # Split the segment into words
    words = segment.strip().split()
    
    if words:
        last_word = words[-1]
        
        # Check if last word ends with 'n'
        if not last_word.endswith('n'):
            # Insert 'n' before 'g' if it exists in the last word
            if 'g' in last_word:
                last_word = last_word[:last_word.rfind('g')] + 'n' + last_word[last_word.rfind('g'):]
            # Update the last word in the words list
            words[-1] = last_word
    
    return " ".join(words)

# Main function to process the input string
def process_string(input_string):
    # Define punctuation marks to split by
    punctuation_marks = r'[,.!?]'
    
    # Split the string by punctuation marks and keep the punctuation
    segments = re.split(f'({punctuation_marks})', input_string)
    
    # Initialize a list to store the modified segments
    modified_segments = []
    
    # Process each segment
    for segment in segments:
        # If the segment is non-empty and not punctuation, process it
        if segment and segment.strip() not in [",", ".", "?", "!"]:
            modified_segments.append(modify_last_word(segment))
        else:
            modified_segments.append(segment)  # Keep punctuation marks as they are
    
    # Join the segments back into a single string
    return "".join(modified_segments)

# Example input
input_string = translated_sentence

# Process the string
result = process_string(input_string)
result = result.capitalize()
# Print the result
print(result)
