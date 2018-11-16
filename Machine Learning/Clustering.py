#Importing the libraries
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
from sklearn.cluster import KMeans 
import random

#Loading the dataset
data= pd.read_csv("sales_data_clustering.csv")
data.head()

#Assigning int ids to genders
data['Gender'] = data['Gender'].map({"Male":0, "Female":1})
data.head()


#Scattering variables on axis
plt.scatter(data['Age'], data['Quantity_purchased'])
plt.xlim(10, 90)
plt.ylim(-1, 15)
plt.show()

#Allocating required colomns to the variable x
x = data.iloc[:, 3:5]
x.head()

#Fitting the kmeans instance to our dataset
kmeans = KMeans(3)
kmeans.fit(x)

#Predicting clusters
identified_clusters = kmeans.fit_predict(x)
data_with_clusters = data.copy()
data_with_clusters['Cluster'] = identified_clusters

# Increasing Column value by two where Gender is 1
data_with_clusters.loc[data_with_clusters.Gender == 1, "Cluster"] += 3

#Visualizing the final graph with clusters
plt.scatter(data_with_clusters['Age'], data_with_clusters['Quantity_purchased'], c = data_with_clusters['Gender'], cmap='rainbow')
plt.legend(('Male','Female'))
plt.title('Clusters of customers')
plt.xlim(10, 90)
plt.ylim(-1, 15)
#Printing the labels on the graph
plt.xlabel('Quantity Sold in Units')
plt.ylabel('Age in years')
plt.show()