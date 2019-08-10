#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Fri Jun 14 16:33:25 2019

@author: frankkempf
"""
import pprint
import spacy
#import numpy as np

from spacy.tokens import Span
pp = pprint.PrettyPrinter(indent=4)

nlp = spacy.load("de")

import os
 
dirpath = os.getcwd()
print("current directory is : " + dirpath)
foldername = os.path.basename(dirpath)
print("Directory name is : " + foldername)


file = open("/Users/frankkempf/anaconda/triageAI/AI/sampletext.spacy.txt", "r") 
strText = file.read() 
file.close()

#doc = nlp(u"This is a sentence built on June 15 2019 costs where 1000 €.")
doc = nlp(strText)
#pp.pprint("ABC123") 
pp.pprint([(x.text, x.label_) for x in doc.ents])
#for token in doc:
#    print(token)



for ent in doc.ents:
    print(ent.text, ent.start_char, ent.end_char, ent.label_)



doc.ents = [Span(doc, 0, 1, label=doc.vocab.strings[u"ORG"])]
for ent in doc.ents:
    print(ent.text, ent.start_char, ent.end_char, ent.label_)

print("END!") 