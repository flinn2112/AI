#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Sun May 12 07:34:42 2019
https://stanford.edu/~shervine/blog/keras-how-to-generate-data-on-the-fly
https://treyhunner.com/2018/10/asterisks-in-python-what-they-are-and-how-to-use-them/#Positional_arguments_with_keyword-only_arguments
@author: Afshine Amidi and Shervine Amidi
"""

import numpy as np
import sys

from keras.models import Sequential
from clsGenerator import DataGenerator

# Parameters
params = {'dim': (32,32,32),
          'batch_size': 64,
          'n_classes': 6,
          'n_channels': 1,
          'shuffle': True}
sys.exit()
# Datasets
#partition = # IDs
#labels = # Labels

# Generators
training_generator = DataGenerator(partition['train'], labels, **params)
validation_generator = DataGenerator(partition['validation'], labels, **params)

# Design model
model = Sequential()
[...] # Architecture
model.compile()

# Train model on dataset
model.fit_generator(generator=training_generator,
                    validation_data=validation_generator,
                    use_multiprocessing=True,
                    workers=6)