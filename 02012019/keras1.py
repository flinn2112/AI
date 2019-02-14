#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Mon Dec 31 08:52:21 2018
Hi
@author: frankkempf



"""
import sys
import tensorflow as tf  
# Create your first MLP in Keras
from tensorflow import keras as keras
from keras.models import Sequential
from keras.layers import Dense
from keras.callbacks import ModelCheckpoint
import numpy as np
# fix random seed for reproducibility
np.random.seed(7)
# load pima indians dataset
dataset = np.loadtxt("training_data.csv", delimiter=" ")
# split into input (X) and output (Y) variables
#originalwert war 0:8
X = dataset[:,0:24]
print(X.shape)
#orginalwert war ,8
Y = dataset[:,5]
print(Y.shape)




# create model
model = Sequential()
#dim war im Beispiel 8
model.add(Dense(12, input_dim=24, activation='relu'))
model.add(Dense(8, activation='relu'))
model.add(Dense(1, activation='sigmoid'))



# Compile model
model.compile(loss='binary_crossentropy', optimizer='adam', metrics=['accuracy'])
# Fit the model
filepath="weights.best.hdf5"
checkpoint = ModelCheckpoint(filepath, monitor='val_acc', verbose=1, save_best_only=True, mode='max')
callbacks_list = [checkpoint]

#model.fit(X, Y, epochs=75, batch_size=50)
model.fit(X, Y, validation_split=0.33, epochs=50, batch_size=50, callbacks=callbacks_list, verbose=0)
# evaluate the model
scores = model.evaluate(X, Y)
print("\n%s: %.2f%%" % (model.metrics_names[1], scores[1]*100))
