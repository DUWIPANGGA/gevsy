import re
import logging
import os
from datetime import datetime

DEBUG_LOG_PATH = os.path.join(os.path.dirname(os.path.abspath(__file__)), "cleaning_debug.log")

# Bersihkan log setiap kali server restart
_ = open(DEBUG_LOG_PATH, "w", encoding="utf-8").close()

def log_debug(msg: str):
    with open(DEBUG_LOG_PATH, "a", encoding="utf-8") as f:
        f.write(f"[{datetime.now().strftime('%H:%M:%S.%f')}] {msg}\n")

HALLUCINATIONS = [
    "terima kasih",
    "terima kasih kerana menonton",
    "terima kasih banyak",
    "thank you",
    "thank you for watching",
    "nonton",
    "menonton"
]


def filter_hallucinations(text: str) -> str:
    if not text:
        return text
    text_clean = text.lower().strip().replace(".", "").replace(",", "").replace("!", "")
    if text_clean in HALLUCINATIONS:
        logging.info(f"Filtered out hallucination: '{text}'")
        return ""
    return text


def remove_consecutive_repetition(text: str) -> str:
    return re.sub(r'\b(\w+)(?:\s+\1\b)+', r'\1', text, flags=re.IGNORECASE)


def normalize_char_repetition(text: str) -> str:
    def fix_word(m):
        word = m.group(0)
        return re.sub(r'(.)\1{2,}', r'\1\1', word)
    return re.sub(r'\b\w+\b', fix_word, text)


def normalize_spaces(text: str) -> str:
    text = re.sub(r'\s+([.,!?;:])', r'\1', text)
    text = re.sub(r' {2,}', ' ', text)
    return text.strip()


def capitalize_text(text: str) -> str:
    if text and text[0].isalpha():
        text = text[0].upper() + text[1:]
    return text


def clean(text: str) -> str:
    if not text:
        return text

    log_debug(f"BEFORE CLEAN: {text!r}")
    text = filter_hallucinations(text)
    if not text:
        log_debug(f"AFTER HALLUCINATION FILTER: (empty - filtered out)")
        return text

    log_debug(f"AFTER HALLUCINATION FILTER: {text!r}")
    text = normalize_char_repetition(text)
    log_debug(f"AFTER CHAR REPETITION: {text!r}")

    text = remove_consecutive_repetition(text)
    log_debug(f"AFTER WORD REPETITION: {text!r}")

    text = normalize_spaces(text)
    log_debug(f"AFTER SPACE NORMALIZE: {text!r}")

    text = capitalize_text(text)
    log_debug(f"AFTER CAPITALIZE: {text!r}")
    return text
